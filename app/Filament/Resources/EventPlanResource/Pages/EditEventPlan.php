<?php

namespace App\Filament\Resources\EventPlanResource\Pages;

use App\Filament\Resources\EventPlanResource;
use App\Models\EventPlan;
use App\Services\AiService;
use App\Services\BudgetForecastService;
use App\Services\PricingStrategyService;
use App\Services\RiskAssessmentService;
use App\Services\RundownGeneratorService;
use App\Services\SyncToLiveService;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\View;
use Illuminate\Support\Str;

/**
 * Edit page for EventPlan with AI-powered planning tools.
 *
 * Provides comprehensive AI-assisted planning capabilities:
 * - Concept Builder: Generate event concepts and descriptions
 * - Budget Forecaster: Estimate detailed budget breakdown by category
 * - Pricing Strategy: Suggest optimal ticket pricing tiers
 * - Risk Assessment: Evaluate and score potential event risks
 *
 * Only Super Admin users can access this resource (per AGENTS.md permission structure).
 */
class EditEventPlan extends EditRecord
{
    protected static string $resource = EventPlanResource::class;

    /**
     * Authorize the user to edit this EventPlan.
     *
     * Only the creating user or Super Admin can edit.
     * This is enforced by the EventPlanPolicy.
     *
     * @param string|int $record The record ID to mount
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function mount(string|int $record): void
    {
        parent::mount($record);

        abort_unless(
            auth()->user()->can('update', $this->record),
            403,
            __('filament-shield::filament-shield.denied')
        );
    }

    /**
     * Execute the mutateFormDataBeforeSave hook to process currency fields.
     *
     * Converts money fields from formatted strings (with thousand separators) to numeric values.
     * Captures the current authenticated user ID for audit trail.
     *
     * @param array<string, mixed> $data The form data to mutate
     * @return array<string, mixed> The mutated data with processed currency fields
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        if (isset($data['budget_target'])) {
            $data['budget_target'] = (float) str_replace('.', '', $data['budget_target']);
        }
        if (isset($data['revenue_target'])) {
            $data['revenue_target'] = (float) str_replace('.', '', $data['revenue_target']);
        }

        // Preserve concept fields
        foreach (['concept_status', 'theme', 'tagline', 'narrative_summary'] as $field) {
            if (! isset($data[$field])) {
                $data[$field] = $this->record->{$field};
            }
        }

        return $data;
    }

    /**
     * Get header actions (AI tools and delete buttons).
     *
     * Returns grouped AI actions and destructive actions with proper permissions and confirmations.
     *
     * @return array<Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                $this->getAiConceptBuilderAction(),
                $this->getApplyConceptToEventAction(),
                $this->getMarkConceptFinalizedAction(),
            ])
            ->label(__('event-planner.action_groups.concept'))
            ->icon('heroicon-m-sparkles')
            ->color('info')
            ->button(),

            Actions\ActionGroup::make([
                $this->getAiBudgetForecastAction(),
                $this->getApplyBudgetToLineItemsAction(),
            ])
            ->label(__('event-planner.action_groups.budget'))
            ->icon('heroicon-m-calculator')
            ->color('warning')
            ->button(),

            Actions\ActionGroup::make([
                $this->getAiPricingStrategyAction(),
                $this->getSelectPricingScenarioAction(),
                $this->getApplyPricingToTicketsAction(),
            ])
            ->label(__('event-planner.action_groups.pricing'))
            ->icon('heroicon-m-banknotes')
            ->color('success')
            ->button(),

            Actions\ActionGroup::make([
                $this->getAiRundownGeneratorAction(),
            ])
            ->label(__('event-planner.action_groups.rundown'))
            ->icon('heroicon-m-queue-list')
            ->color('info')
            ->button(),

            $this->getAiRiskAssessmentAction(),

            Actions\ActionGroup::make([
                $this->getSyncToLiveAction(),
                $this->getCreateEventFromPlanAction(),
            ])
            ->label(__('event-planner.action_groups.sync'))
            ->icon('heroicon-m-arrow-up-on-square')
            ->color('gray')
            ->button(),

            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    // ─────────────────────────────────────────────────────────────
    //  5.1 + 5.2  AI Concept Builder
    // ─────────────────────────────────────────────────────────────

    /**
     * Mark the concept status as "finalized".
     *
     * @return Action
     */
    protected function getMarkConceptFinalizedAction(): Action
    {
        return Action::make('markConceptFinalized')
            ->label(__('event-planner.concept_narrative.mark_finalized'))
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->visible(fn (): bool => in_array($this->record->concept_status ?? 'brainstorm', ['drafted']))
            ->requiresConfirmation()
            ->modalHeading(__('event-planner.concept_narrative.mark_finalized'))
            ->modalDescription(__('event-planner.concept_narrative.mark_finalized_confirm'))
            ->action(function (): void {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $plan->update(['concept_status' => 'finalized']);
                $this->record = $plan->fresh();
                $this->fillForm();
                Notification::make()
                    ->success()
                    ->title(__('event-planner.concept_narrative.mark_finalized_success'))
                    ->send();
            });
    }

    /**
     * Build AI Concept Generator action.
     *
     * Generates event concepts based on event details using AI service.
     * Can regenerate existing concepts with confirmation.
     *
     * @return Action
     */
    protected function getAiConceptBuilderAction(): Action
    {
        return Action::make('aiConceptBuilder')
            ->label(__('event-planner.ai_actions.concept_builder.action_label'))
            ->icon('heroicon-o-sparkles')
            ->color('info')
            ->requiresConfirmation(fn (): bool => !empty($this->record->ai_concept_result))
            ->modalHeading(fn (): string => !empty($this->record->ai_concept_result)
                ? __('event-planner.ai_actions.concept_builder.regenerate_label')
                : __('event-planner.ai_actions.concept_builder.action_label'))
            ->modalDescription(fn (): ?string => !empty($this->record->ai_concept_result)
                ? __('event-planner.ai_actions.concept_builder.regenerate_confirm')
                : __('event-planner.ai_actions.concept_builder.description'))
            ->modalWidth('2xl')
            ->form([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('event-planner.labels.title'))
                            ->default(fn () => $this->record->title)
                            ->disabled(),

                        TextInput::make('event_category')
                            ->label(__('event-planner.labels.event_category'))
                            ->default(fn () => $this->record->event_category)
                            ->disabled(),

                        TextInput::make('target_audience_size')
                            ->label(__('event-planner.labels.target_audience_size'))
                            ->numeric()
                            ->default(fn () => $this->record->target_audience_size)
                            ->disabled(),

                        TextInput::make('revenue_target')
                            ->label(__('event-planner.labels.revenue_target'))
                            ->numeric()
                            ->prefix('Rp')
                            ->default(fn () => $this->record->revenue_target)
                            ->disabled(),

                        Textarea::make('target_audience_description')
                            ->label(__('event-planner.labels.target_audience_description'))
                            ->rows(3)
                            ->default(fn () => $this->record->target_audience_description)
                            ->disabled()
                            ->columnSpan(2),

                        RichEditor::make('ai_concept_result')
                            ->label(__('event-planner.ai_actions.concept_builder.ai_result_label'))
                            ->default(fn () => $this->record->ai_concept_result)
                            ->disabled()
                            ->columnSpan(2)
                            ->helperText(__('event-planner.ai_actions.concept_builder.ai_result_helper')),
                    ]),
            ])
            ->action(function (AiService $aiService) {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $this->save();
                $plan->refresh();

                $result = $aiService->generateConcept($plan->toArray());

                if ($result) {
                    $updates = ['ai_concept_result' => $result, 'concept_status' => 'drafted'];

                    // Try to extract theme and tagline from the generated HTML content
                    if (preg_match('/<h[12][^>]*>(.*?)<\/h[12]>/i', $result, $taglineMatch)) {
                        $taglineText = strip_tags($taglineMatch[1]);
                        if (strlen($taglineText) <= 255) {
                            $updates['tagline'] = $taglineText;
                        }
                    }

                    // Extract first paragraph as narrative summary
                    if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $result, $paraMatch)) {
                        $summaryText = strip_tags($paraMatch[1]);
                        if (strlen($summaryText) > 10) {
                            $updates['narrative_summary'] = substr($summaryText, 0, 1000);
                        }
                    }

                    $plan->update($updates);
                    $this->record = $plan->fresh();
                    $this->fillForm();
                    Notification::make()
                        ->success()
                        ->title(__('event-planner.ai_actions.concept_builder.success'))
                        ->send();
                } else {
                    Notification::make()
                        ->danger()
                        ->title('AI Concept Builder')
                        ->body('AI concept generation failed.')
                        ->send();
                }
            });
    }

    // ─────────────────────────────────────────────────────────────
    //  5.3  AI Budget Forecast + Apply to Budget
    // ─────────────────────────────────────────────────────────────

    /**
     * Apply AI-generated concept to linked event description.
     *
     * Updates the event description with the AI-generated concept result.
     * Only available if event is linked and concept has been generated.
     *
     * @return Action
     */
    protected function getApplyConceptToEventAction(): Action
    {
        return Action::make('applyConceptToEvent')
            ->label(__('event-planner.ai_actions.concept_builder.apply_label'))
            ->icon('heroicon-o-arrow-up-tray')
            ->color('info')
            ->visible(fn (): bool => !empty($this->record->ai_concept_result) && !empty($this->record->event_id))
            ->requiresConfirmation()
            ->modalHeading(__('event-planner.ai_actions.concept_builder.apply_label'))
            ->modalDescription(__('event-planner.ai_actions.concept_builder.apply_confirm'))
            ->action(function () {
                /** @var EventPlan $plan */
                $plan = $this->record;

                if (!$plan->event_id || empty($plan->ai_concept_result)) {
                    Notification::make()
                        ->warning()
                        ->title(empty($plan->ai_concept_result)
                            ? __('event-planner.ai_actions.concept_builder.no_ai_result')
                            : __('event-planner.ai_actions.concept_builder.no_linked_event'))
                        ->send();
                    return;
                }

                $plan->event()->update(['description' => $plan->ai_concept_result]);

                Notification::make()
                    ->success()
                    ->title(__('event-planner.ai_actions.concept_builder.apply_success'))
                    ->send();
            });
    }

    /**
     * Apply budget forecast results to line items.
     *
     * Populates EventPlanLineItem records from AI budget forecast results.
     *
     * @return Action
     */
    protected function getApplyBudgetToLineItemsAction(): Action
    {
        return Action::make('applyBudgetToLineItems')
            ->label(__('event-planner.ai_actions.budget_forecast.apply_label'))
            ->icon('heroicon-o-arrow-up-tray')
            ->color('warning')
            ->visible(fn (): bool => !empty($this->record->ai_budget_result))
            ->requiresConfirmation()
            ->modalHeading(__('event-planner.ai_actions.budget_forecast.apply_label'))
            ->modalDescription(fn (): string => __(
                'event-planner.ai_actions.budget_forecast.apply_confirm',
                ['count' => count(($this->record->ai_budget_result['categories'] ?? []))]
            ))
            ->action(function (BudgetForecastService $budgetForecastService) {
                /** @var EventPlan $plan */
                $plan = $this->record;

                if (empty($plan->ai_budget_result)) {
                    Notification::make()
                        ->warning()
                        ->title(__('event-planner.ai_actions.budget_forecast.no_ai_result'))
                        ->send();
                    return;
                }

                $count = $budgetForecastService->populateLineItems($plan, $plan->ai_budget_result);

                Notification::make()
                    ->success()
                    ->title(__('event-planner.ai_actions.budget_forecast.apply_success', ['count' => $count]))
                    ->send();
            });
    }

    /**
     * Build AI Budget Forecaster action.
     *
     * Generates budget breakdown by category based on target audience and revenue.
     *
     * @return Action
     */
    protected function getAiBudgetForecastAction(): Action
    {
        return Action::make('aiBudgetForecast')
            ->label(__('event-planner.ai_actions.budget_forecast.action_label'))
            ->icon('heroicon-o-calculator')
            ->color('warning')
            ->action(function (BudgetForecastService $budgetForecastService) {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $this->save();
                $plan->refresh();

                $errors = $budgetForecastService->validate($plan);
                if (!empty($errors)) {
                    Notification::make()
                        ->warning()
                        ->title(__('event-planner.ai_actions.budget_forecast.action_label'))
                        ->body(implode(' ', $errors))
                        ->send();
                    return;
                }

                $result = $budgetForecastService->forecast($plan);

                if ($result && empty($result['errors'])) {
                    $this->record = $plan->fresh();
                    Notification::make()
                        ->success()
                        ->title(__('event-planner.ai_actions.budget_forecast.success'))
                        ->send();
                }
            });
    }

    // ─────────────────────────────────────────────────────────────
    //  5.4 + 5.5  AI Pricing Strategy + Apply to Event Tickets
    // ─────────────────────────────────────────────────────────────

    /**
     * Build AI Pricing Strategy action.
     *
     * Generates three pricing scenarios (pessimistic / realistic / optimistic).
     * After generation, shows a scenario-selection modal so the user can pick
     * which scenario to apply to the live event.
     *
     * @return Action
     */
    protected function getAiPricingStrategyAction(): Action
    {
        return Action::make('aiPricingStrategy')
            ->label(__('event-planner.ai_actions.pricing_strategy.action_label'))
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->action(function (PricingStrategyService $pricingStrategyService) {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $this->save();
                $plan->refresh();

                $errors = $pricingStrategyService->validate($plan);
                if (!empty($errors)) {
                    Notification::make()
                        ->warning()
                        ->title(__('event-planner.ai_actions.pricing_strategy.action_label'))
                        ->body(implode(' ', $errors))
                        ->send();
                    return;
                }

                $result = $pricingStrategyService->suggest($plan);

                if ($result && empty($result['errors'])) {
                    $this->record = $plan->fresh();
                    Notification::make()
                        ->success()
                        ->title(__('event-planner.ai_actions.pricing_strategy.success'))
                        ->body(__('event-planner.pricing_scenarios.generated_hint'))
                        ->send();
                }
            });
    }

    /**
     * Select which pricing scenario to use when applying to event tickets.
     *
     * Presents all available scenarios with their tier breakdown and allows
     * the user to mark one as the selected scenario.
     *
     * @return Action
     */
    protected function getSelectPricingScenarioAction(): Action
    {
        return Action::make('selectPricingScenario')
            ->label(__('event-planner.pricing_scenarios.select_label'))
            ->icon('heroicon-o-adjustments-horizontal')
            ->color('success')
            ->visible(fn (): bool => isset($this->record->ai_pricing_result['scenarios']))
            ->modalHeading(__('event-planner.pricing_scenarios.modal_heading'))
            ->modalWidth('4xl')
            ->form(function (): array {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $pricing = $plan->ai_pricing_result ?? [];
                $scenarios = $pricing['scenarios'] ?? [];
                $currentSelected = $pricing['selected_scenario'] ?? 'realistic';

                $options = [];
                $scenarioDetails = '<div class="space-y-4">';

                foreach ($scenarios as $key => $scenario) {
                    $label = $scenario['label'] ?? ucfirst((string) $key);
                    $options[$key] = $label;

                    $tiers = $scenario['tiers'] ?? [];
                    $revenue = $scenario['total_projected_revenue'] ?? 0;
                    $targetMet = $scenario['target_met'] ?? false;
                    $surplus = $scenario['surplus_deficit'] ?? 0;

                    $tierRows = '';
                    foreach ($tiers as $tier) {
                        $tierRows .= '<tr>'
                            . '<td class="px-2 py-1 text-sm">' . e($tier['name'] ?? '—') . '</td>'
                            . '<td class="px-2 py-1 text-sm text-right">Rp ' . number_format((float)($tier['price'] ?? 0), 0, ',', '.') . '</td>'
                            . '<td class="px-2 py-1 text-sm text-right">' . number_format((int)($tier['quantity'] ?? 0), 0, ',', '.') . '</td>'
                            . '</tr>';
                    }

                    $badgeColor = match($key) {
                        'pessimistic' => '#ef4444',
                        'optimistic'  => '#22c55e',
                        default       => '#3b82f6',
                    };

                    $targetBadge = $targetMet
                        ? '<span class="text-xs text-green-600 font-semibold">✓ ' . __('event-planner.pricing_scenarios.target_met') . '</span>'
                        : '<span class="text-xs text-red-600 font-semibold">✗ ' . __('event-planner.pricing_scenarios.target_not_met') . '</span>';

                    $surplusText = $surplus >= 0
                        ? '<span class="text-green-600">+Rp ' . number_format((float)$surplus, 0, ',', '.') . '</span>'
                        : '<span class="text-red-600">−Rp ' . number_format((float)abs($surplus), 0, ',', '.') . '</span>';

                    $scenarioDetails .= '<div class="rounded-lg border p-3">'
                        . '<div class="flex items-center gap-2 mb-2">'
                        . '<span class="inline-block rounded px-2 py-0.5 text-xs font-bold text-white" style="background:' . $badgeColor . '">' . e($label) . '</span>'
                        . $targetBadge
                        . '</div>'
                        . '<div class="text-xs text-gray-500 mb-2">'
                        . __('event-planner.pricing_scenarios.projected_revenue') . ': <strong>Rp ' . number_format((float)$revenue, 0, ',', '.') . '</strong>'
                        . ' &nbsp;|&nbsp; '
                        . __('event-planner.pricing_scenarios.surplus_deficit') . ': ' . $surplusText
                        . '</div>'
                        . '<table class="w-full text-xs"><thead><tr>'
                        . '<th class="px-2 py-1 text-left">' . __('event-planner.pricing_scenarios.tier_name') . '</th>'
                        . '<th class="px-2 py-1 text-right">' . __('event-planner.pricing_scenarios.price') . '</th>'
                        . '<th class="px-2 py-1 text-right">' . __('event-planner.pricing_scenarios.quantity') . '</th>'
                        . '</tr></thead><tbody>' . $tierRows . '</tbody></table>'
                        . '</div>';
                }

                $scenarioDetails .= '</div>';

                return [
                    \Filament\Forms\Components\Select::make('selected_scenario')
                        ->label(__('event-planner.pricing_scenarios.select_scenario'))
                        ->options($options)
                        ->default($currentSelected)
                        ->required(),
                    View::make('filament.pricing-scenarios-preview')
                        ->viewData(['html' => $scenarioDetails]),
                ];
            })
            ->action(function (array $data): void {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $pricing = $plan->ai_pricing_result ?? [];

                if (! isset($pricing['scenarios'])) {
                    return;
                }

                $pricing['selected_scenario'] = $data['selected_scenario'];
                $plan->update(['ai_pricing_result' => $pricing]);
                $this->record = $plan->fresh();

                Notification::make()
                    ->success()
                    ->title(__('event-planner.pricing_scenarios.scenario_selected', [
                        'scenario' => ucfirst((string) $data['selected_scenario']),
                    ]))
                    ->send();
            });
    }

    /**
     * Apply AI-generated pricing strategy to event ticket types.
     *
     * Uses tiers from the currently selected scenario only.
     * Only available if event is linked and pricing has been generated.
     *
     * @return Action
     */
    protected function getApplyPricingToTicketsAction(): Action
    {
        return Action::make('applyPricingToTickets')
            ->label(__('event-planner.ai_actions.pricing_strategy.apply_label'))
            ->icon('heroicon-o-ticket')
            ->color('success')
            ->visible(fn (): bool => !empty($this->record->ai_pricing_result))
            ->requiresConfirmation()
            ->modalHeading(__('event-planner.ai_actions.pricing_strategy.apply_label'))
            ->modalDescription(function (PricingStrategyService $pricingStrategyService): string {
                /** @var EventPlan $plan */
                $plan = $this->record;

                if (!$plan->event_id) {
                    return __('event-planner.ai_actions.pricing_strategy.no_linked_event');
                }

                $tiers = $pricingStrategyService->getSelectedTiers($plan->ai_pricing_result ?? []);
                $count = count($tiers);
                $selected = $plan->ai_pricing_result['selected_scenario'] ?? 'realistic';

                return __('event-planner.ai_actions.pricing_strategy.apply_confirm_scenario', [
                    'count'    => $count,
                    'scenario' => ucfirst((string) $selected),
                ]);
            })
            ->action(function (PricingStrategyService $pricingStrategyService) {
                /** @var EventPlan $plan */
                $plan = $this->record;

                if (!$plan->event_id) {
                    Notification::make()
                        ->warning()
                        ->title(__('event-planner.ai_actions.pricing_strategy.no_linked_event'))
                        ->send();
                    return;
                }

                if (empty($plan->ai_pricing_result)) {
                    Notification::make()
                        ->warning()
                        ->title(__('event-planner.ai_actions.pricing_strategy.no_ai_result'))
                        ->send();
                    return;
                }

                $tiers = $pricingStrategyService->getSelectedTiers($plan->ai_pricing_result);
                $count = $pricingStrategyService->applyToEvent($plan, $tiers);

                Notification::make()
                    ->success()
                    ->title(__('event-planner.ai_actions.pricing_strategy.apply_success', ['count' => $count]))
                    ->send();
            });
    }

    // ─────────────────────────────────────────────────────────────
    //  5.7  AI Rundown Generator (Phase 7)
    // ─────────────────────────────────────────────────────────────

    /**
     * Generate an AI-suggested event rundown and preview it before applying.
     *
     * The modal shows a preview table of items; the user can choose to
     * append them to the plan's existing rundown.
     *
     * @return Action
     */
    protected function getAiRundownGeneratorAction(): Action
    {
        return Action::make('aiRundownGenerator')
            ->label(__('event-planner.ai_actions.rundown_generator.action_label'))
            ->icon('heroicon-o-queue-list')
            ->color('info')
            ->modalHeading(__('event-planner.ai_actions.rundown_generator.modal_heading'))
            ->modalWidth('3xl')
            ->modalSubmitActionLabel(__('event-planner.ai_actions.rundown_generator.apply_label'))
            ->form(function (RundownGeneratorService $rundownGeneratorService): array {
                /** @var EventPlan $plan */
                $plan = $this->record;

                $errors = $rundownGeneratorService->validate($plan);
                if (!empty($errors)) {
                    return [
                        \Filament\Forms\Components\Placeholder::make('error')
                            ->label('')
                            ->content(implode(' ', $errors)),
                    ];
                }

                $items = $rundownGeneratorService->generate($plan) ?? [];

                // Store generated items in a hidden JSON field for the action step
                $itemsJson = json_encode($items);

                $rows = '';
                foreach ($items as $item) {
                    $start = $item['start_time'] ?? '—';
                    $end   = $item['end_time'] ?? '—';
                    $type  = $item['type'] ?? 'other';
                    $rows .= '<tr>'
                        . '<td class="px-2 py-1 text-sm">' . e($item['title'] ?? '—') . '</td>'
                        . '<td class="px-2 py-1 text-sm text-center">' . e($start) . ' – ' . e($end) . '</td>'
                        . '<td class="px-2 py-1 text-sm text-center capitalize">' . e($type) . '</td>'
                        . '</tr>';
                }

                $previewHtml = empty($items)
                    ? '<p class="text-sm text-gray-400">' . __('event-planner.ai_actions.rundown_generator.empty') . '</p>'
                    : '<div class="overflow-auto max-h-80"><table class="w-full text-xs">'
                        . '<thead><tr>'
                        . '<th class="px-2 py-1 text-left">' . __('event_plan_rundown.title') . '</th>'
                        . '<th class="px-2 py-1 text-center">' . __('event_plan_rundown.time_range') . '</th>'
                        . '<th class="px-2 py-1 text-center">' . __('event_plan_rundown.type') . '</th>'
                        . '</tr></thead>'
                        . '<tbody>' . $rows . '</tbody>'
                        . '</table></div>';

                return [
                    \Filament\Forms\Components\Hidden::make('_rundown_items')
                        ->default($itemsJson),

                    View::make('filament.pricing-scenarios-preview')
                        ->viewData(['html' => $previewHtml]),
                ];
            })
            ->action(function (array $data, RundownGeneratorService $rundownGeneratorService): void {
                /** @var EventPlan $plan */
                $plan = $this->record;

                /** @var array<int, array<string, mixed>> $items */
                $items = json_decode($data['_rundown_items'] ?? '[]', true) ?? [];

                if (empty($items)) {
                    Notification::make()
                        ->warning()
                        ->title(__('event-planner.ai_actions.rundown_generator.empty'))
                        ->send();
                    return;
                }

                $count = $rundownGeneratorService->apply($plan, $items);

                Notification::make()
                    ->success()
                    ->title(__('event-planner.ai_actions.rundown_generator.apply_success', ['count' => $count]))
                    ->send();
            });
    }

    // ─────────────────────────────────────────────────────────────
    //  5.6  AI Risk Assessment
    // ─────────────────────────────────────────────────────────────

    /**
     * Build AI Risk Assessment action.
     *
     * Evaluates potential event risks with severity scoring.
     *
     * @return Action
     */
    protected function getAiRiskAssessmentAction(): Action
    {
        return Action::make('aiRiskAssessment')
            ->label(__('event-planner.ai_actions.risk_assessment.action_label'))
            ->icon('heroicon-o-shield-exclamation')
            ->color('danger')
            ->action(function (RiskAssessmentService $riskAssessmentService) {
                /** @var EventPlan $plan */
                $plan = $this->record;
                $this->save();
                $plan->refresh();

                $result = $riskAssessmentService->assess($plan);

                if ($result) {
                    $this->record = $plan->fresh();
                    Notification::make()
                        ->success()
                        ->title(__('event-planner.ai_actions.risk_assessment.success'))
                        ->send();
                }
            });
    }

    // ─────────────────────────────────────────────────────────────
    //  Phase 8.9  Sync to Live Action
    // ─────────────────────────────────────────────────────────────

    /**
     * Sync selected sections of the EventPlan to the linked live Event.
     *
     * Shows a diff preview with per-section checkboxes; executes the
     * sync in a DB transaction on confirm.
     *
     * @return Action
     */
    protected function getSyncToLiveAction(): Action
    {
        return Action::make('syncToLive')
            ->label(__('sync_to_live.action_label'))
            ->icon('heroicon-o-arrow-up-on-square')
            ->color('gray')
            ->visible(fn (): bool => ! empty($this->record->event_id))
            ->modalHeading(__('sync_to_live.modal_heading'))
            ->modalWidth('3xl')
            ->modalSubmitActionLabel(__('sync_to_live.confirm_label'))
            ->form(function (SyncToLiveService $syncToLiveService): array {
                /** @var EventPlan $plan */
                $plan = $this->record;

                $errors = $syncToLiveService->validateSync($plan);
                if (! empty($errors)) {
                    return [
                        \Filament\Forms\Components\Placeholder::make('error')
                            ->label('')
                            ->content(implode(' | ', $errors)),
                    ];
                }

                $diff = $syncToLiveService->generateDiff($plan);

                $checkboxes = [];
                foreach ($diff as $key => $section) {
                    $changed = (bool) ($section['changed'] ?? false);
                    $label   = $section['label'] ?? ucfirst((string) $key);

                    $description = '';
                    if (isset($section['items']) && is_array($section['items'])) {
                        $description = implode(', ', array_slice($section['items'], 0, 3));
                        if (count($section['items']) > 3) {
                            $description .= ' ...';
                        }
                    } elseif (isset($section['plan']) && is_string($section['plan'])) {
                        $description = Str::limit($section['plan'], 80);
                    }

                    $checkboxes[] = \Filament\Forms\Components\Toggle::make("sections.{$key}")
                        ->label($label . ($changed ? ' ✱' : ''))
                        ->helperText($description ?: ($changed ? __('sync_to_live.has_changes') : __('sync_to_live.no_changes')))
                        ->default($changed);
                }

                return $checkboxes ?: [
                    \Filament\Forms\Components\Placeholder::make('no_diff')
                        ->label('')
                        ->content(__('sync_to_live.no_changes_found')),
                ];
            })
            ->action(function (array $data, SyncToLiveService $syncToLiveService): void {
                /** @var EventPlan $plan */
                $plan = $this->record;

                $sections = $data['sections'] ?? [];

                if (empty(array_filter($sections))) {
                    Notification::make()
                        ->warning()
                        ->title(__('sync_to_live.no_sections_selected'))
                        ->send();
                    return;
                }

                $summary = $syncToLiveService->executeSync($plan, $sections);
                $this->record = $plan->fresh();

                Notification::make()
                    ->success()
                    ->title(__('sync_to_live.success'))
                    ->body(collect($summary)->map(fn ($v, $k) => __("sync_to_live.sections.{$k}") . ': ' . $v)->implode(' | '))
                    ->send();
            });
    }

    // ─────────────────────────────────────────────────────────────
    //  Phase 8.10  Create Event from Plan Action
    // ─────────────────────────────────────────────────────────────

    /**
     * Create a new draft Event from a standalone (unlinked) plan.
     *
     * Only visible when the plan has no linked event.
     *
     * @return Action
     */
    protected function getCreateEventFromPlanAction(): Action
    {
        return Action::make('createEventFromPlan')
            ->label(__('sync_to_live.create_event_label'))
            ->icon('heroicon-o-calendar-days')
            ->color('primary')
            ->visible(fn (): bool => empty($this->record->event_id))
            ->requiresConfirmation()
            ->modalHeading(__('sync_to_live.create_event_heading'))
            ->modalDescription(__('sync_to_live.create_event_description'))
            ->action(function (SyncToLiveService $syncToLiveService): void {
                /** @var EventPlan $plan */
                $plan = $this->record;

                $event = $syncToLiveService->createEventFromPlan($plan);
                $this->record = $plan->fresh();
                $this->fillForm();

                Notification::make()
                    ->success()
                    ->title(__('sync_to_live.create_event_success', ['title' => $event->title]))
                    ->send();
            });
    }
}
