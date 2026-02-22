<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use App\Models\EventPlan;
use App\Models\TicketType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service for synchronising an EventPlan with a live Event.
 *
 * Workflow:
 *  1. validateSync()      — ensure plan is ready to sync
 *  2. generateDiff()      — produce a structured diff of plan vs event
 *  3. executeSync()       — apply selected sections in a DB transaction
 *  4. createEventFromPlan() — create a new draft Event from a standalone plan
 */
class SyncToLiveService
{
    public function __construct(private PricingStrategyService $pricingStrategyService) {}

    // ─────────────────────────────────────────────────────
    //  Validation
    // ─────────────────────────────────────────────────────

    /**
     * Validate the plan can be synced.
     *
     * @param  EventPlan $plan
     * @return array<string> Validation error messages
     */
    public function validateSync(EventPlan $plan): array
    {
        $errors = [];

        if (! $plan->event_id) {
            $errors[] = __('sync_to_live.validation.no_linked_event');
        }

        if (empty($plan->title)) {
            $errors[] = __('sync_to_live.validation.missing_title');
        }

        return $errors;
    }

    // ─────────────────────────────────────────────────────
    //  Diff Generation
    // ─────────────────────────────────────────────────────

    /**
     * Generate a structured diff between plan data and the linked event.
     *
     * Returns a keyed array — each key is a "section" that can be
     * independently selected/deselected in the UI.
     *
     * @param  EventPlan $plan
     * @return array<string, array<string, mixed>>
     */
    public function generateDiff(EventPlan $plan): array
    {
        $event = $plan->event;

        if (! $event) {
            return [];
        }

        $diff = [];

        // ── Concept ──────────────────────────────────────
        $planConcept = trim(strip_tags((string) ($plan->ai_concept_result ?? '')));
        $eventDesc   = trim(strip_tags((string) ($event->description ?? '')));
        $diff['concept'] = [
            'label'   => __('sync_to_live.sections.concept'),
            'plan'    => $plan->narrative_summary ?? $planConcept,
            'live'    => Str::limit($eventDesc, 200),
            'changed' => $planConcept !== $eventDesc && ! empty($planConcept),
        ];

        // ── Metadata ─────────────────────────────────────
        $diff['metadata'] = [
            'label'   => __('sync_to_live.sections.metadata'),
            'plan' => [
                'title'      => $plan->title,
                'event_date' => $plan->event_date?->toDateString(),
                'location'   => $plan->location,
            ],
            'live' => [
                'title'      => $event->title,
                'event_date' => $event->event_date?->toDateString(),
                'location'   => $event->location,
            ],
            'changed' => $plan->title !== $event->title
                || $plan->event_date?->toDateString() !== $event->event_date?->toDateString()
                || $plan->location !== $event->location,
        ];

        // ── Performers ───────────────────────────────────
        $confirmedTalents = $plan->talents()
            ->where('contract_status', 'confirmed')
            ->with('performer')
            ->get();

        $livePerformerIds = $event->performers()->pluck('performers.id')->toArray();
        $newPerformers = $confirmedTalents->filter(
            fn ($t) => $t->performer_id && ! in_array($t->performer_id, $livePerformerIds)
        );

        $diff['performers'] = [
            'label'           => __('sync_to_live.sections.performers'),
            'new_count'       => $newPerformers->count(),
            'confirmed_count' => $confirmedTalents->count(),
            'changed'         => $newPerformers->isNotEmpty(),
            'items'           => $newPerformers->map(fn ($t) => $t->performer?->name ?? '?')->values()->toArray(),
        ];

        // ── Tickets ──────────────────────────────────────
        $pricingResult = $plan->ai_pricing_result ?? [];
        $hasPricing = isset($pricingResult['scenarios']) || isset($pricingResult['tiers']);
        $selectedTiers = $hasPricing ? $this->pricingStrategyService->getSelectedTiers($pricingResult) : [];
        $selectedScenario = $pricingResult['selected_scenario'] ?? 'realistic';

        $diff['tickets'] = [
            'label'             => __('sync_to_live.sections.tickets'),
            'tier_count'        => count($selectedTiers),
            'selected_scenario' => $selectedScenario,
            'changed'           => ! empty($selectedTiers),
            'items'             => array_map(fn ($t) => ($t['name'] ?? '?') . ' — Rp ' . number_format((float)($t['price'] ?? 0), 0, ',', '.'), $selectedTiers),
        ];

        return $diff;
    }

    // ─────────────────────────────────────────────────────
    //  Execute Sync
    // ─────────────────────────────────────────────────────

    /**
     * Apply selected sections from the plan to the live event.
     *
     * @param  EventPlan            $plan
     * @param  array<string, bool>  $sections  Keys are section names, values are whether to sync
     * @return array<string, int>   Summary of operations performed per section
     */
    public function executeSync(EventPlan $plan, array $sections): array
    {
        $event = $plan->event;
        if (! $event) {
            return [];
        }

        $summary = [];

        DB::transaction(function () use ($plan, $event, $sections, &$summary): void {

            if (! empty($sections['concept'])) {
                $summary['concept'] = $this->syncConcept($plan, $event);
            }

            if (! empty($sections['metadata'])) {
                $summary['metadata'] = $this->syncMetadata($plan, $event);
            }

            if (! empty($sections['performers'])) {
                $summary['performers'] = $this->syncPerformers($plan, $event);
            }

            if (! empty($sections['tickets'])) {
                $summary['tickets'] = $this->syncTickets($plan, $event);
            }

            // Log activity
            activity()
                ->performedOn($plan)
                ->causedBy(auth()->user())
                ->withProperties(['sections' => array_keys(array_filter($sections)), 'event_id' => $event->id])
                ->log('sync_to_live');
        });

        Log::info('SyncToLiveService: Synced plan #' . $plan->id . ' to event #' . $event->id, $summary);

        return $summary;
    }

    // ─────────────────────────────────────────────────────
    //  Section Syncs
    // ─────────────────────────────────────────────────────

    /**
     * Sync concept/description to the live event.
     *
     * @param  EventPlan $plan
     * @param  Event     $event
     * @return int       1 if updated
     */
    protected function syncConcept(EventPlan $plan, Event $event): int
    {
        if (empty($plan->ai_concept_result)) {
            return 0;
        }

        $event->update(['description' => $plan->ai_concept_result]);

        $plan->update([
            'concept_status'    => 'synced',
            'concept_synced_at' => now(),
        ]);

        return 1;
    }

    /**
     * Sync metadata fields (title, event_date, location) to the live event.
     *
     * @param  EventPlan $plan
     * @param  Event     $event
     * @return int       1 if updated
     */
    protected function syncMetadata(EventPlan $plan, Event $event): int
    {
        $updates = [];

        if (! empty($plan->title) && $plan->title !== $event->title) {
            $updates['title'] = $plan->title;
            $updates['slug']  = Str::slug($plan->title);
        }

        if ($plan->event_date && $plan->event_date->toDateString() !== $event->event_date?->toDateString()) {
            $updates['event_date'] = $plan->event_date;
        }

        if (! empty($plan->location) && $plan->location !== $event->location) {
            $updates['location'] = $plan->location;
        }

        if (! empty($updates)) {
            $event->update($updates);
        }

        return empty($updates) ? 0 : 1;
    }

    /**
     * Attach confirmed plan talents as performers on the live event.
     *
     * Only adds performers not already linked to the event.
     *
     * @param  EventPlan $plan
     * @param  Event     $event
     * @return int       Number of performers attached
     */
    protected function syncPerformers(EventPlan $plan, Event $event): int
    {
        $confirmedTalents = $plan->talents()
            ->where('contract_status', 'confirmed')
            ->whereNotNull('performer_id')
            ->pluck('performer_id')
            ->toArray();

        $existingIds = $event->performers()->pluck('performers.id')->toArray();
        $newIds = array_diff($confirmedTalents, $existingIds);

        if (! empty($newIds)) {
            $event->performers()->attach($newIds);
        }

        return count($newIds);
    }

    /**
     * Create TicketType records from the selected pricing scenario.
     *
     * @param  EventPlan $plan
     * @param  Event     $event
     * @return int       Number of ticket types created
     */
    protected function syncTickets(EventPlan $plan, Event $event): int
    {
        $pricingResult = $plan->ai_pricing_result ?? [];
        $tiers = $this->pricingStrategyService->getSelectedTiers($pricingResult);

        return $this->pricingStrategyService->applyToEvent($plan, $tiers);
    }

    // ─────────────────────────────────────────────────────
    //  Create Event from Plan
    // ─────────────────────────────────────────────────────

    /**
     * Create a new draft Event from a standalone (unlinked) EventPlan.
     *
     * Sets event_id on the plan after creation.
     *
     * @param  EventPlan $plan
     * @return Event
     */
    public function createEventFromPlan(EventPlan $plan): Event
    {
        $event = DB::transaction(function () use ($plan): Event {
            $title = $plan->title;
            $slug  = Str::slug($title) . '-' . Str::random(4);

            $event = Event::create([
                'title'       => $title,
                'slug'        => $slug,
                'description' => $plan->ai_concept_result ?? $plan->description ?? '',
                'event_date'  => $plan->event_date ?? Carbon::now()->addMonth(),
                'location'    => $plan->location ?? '',
                'status'      => 'draft',
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ]);

            $plan->update(['event_id' => $event->id]);

            // Log activity
            activity()
                ->performedOn($plan)
                ->causedBy(auth()->user())
                ->withProperties(['event_id' => $event->id])
                ->log('create_event_from_plan');

            return $event;
        });

        Log::info('SyncToLiveService: Created event #' . $event->id . ' from plan #' . $plan->id);

        return $event;
    }
}
