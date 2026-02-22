<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string $image_path
 * @property string|null $link_url
 * @property string $link_target
 * @property int $position
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int $click_count
 * @property int $impression_count
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner byType(?string $type)
 * @method static \Database\Factories\BannerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner scheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereClickCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImpressionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLinkTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\FileBucket|null $fileBucket
 */
	class Banner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array<array-key, mixed> $content
 * @property string $status
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $og_image
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\SeoMetadata|null $seoMetadata
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage draft()
 * @method static \Database\Factories\CmsPageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage withoutTrashed()
 * @mixin \Eloquent
 */
	class CmsPage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $subject
 * @property string $html_content
 * @property string|null $text_content
 * @property array<array-key, mixed>|null $variables
 * @property string $locale
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TemplateVersion> $versions
 * @property-read int|null $versions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate byKey(string $key)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate byLocale(string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate default()
 * @method static \Database\Factories\EmailTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereHtmlContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereTextContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereVariables($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate withoutTrashed()
 * @mixin \Eloquent
 */
	class EmailTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string $description
 * @property \Illuminate\Support\Carbon $event_date
 * @property \Illuminate\Support\Carbon|null $event_end_date
 * @property string $location
 * @property string|null $city_code
 * @property string|null $venue_name
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string|null $google_place_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $sales_start_at
 * @property \Illuminate\Support\Carbon|null $sales_end_at
 * @property bool $seating_enabled
 * @property int $total_capacity
 * @property string|null $cancellation_reason
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\FileBucket|null $banner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileBucket> $banners
 * @property-read int|null $banners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $categories
 * @property-read int|null $categories_count
 * @property-read \Laravolt\Indonesia\Models\City|null $city
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read int $available_tickets
 * @property-read array $banner_images
 * @property-read float|null $from_price
 * @property-read bool $is_draft
 * @property-read bool $is_published
 * @property-read bool $is_sold_out
 * @property-read int $sold_tickets
 * @property-read int $total_favorites
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\EventPlan|null $eventPlan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organizer> $organizers
 * @property-read int|null $organizers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentBank> $paymentBanks
 * @property-read int|null $payment_banks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Performer> $performers
 * @property-read int|null $performers_count
 * @property-read \App\Models\SeoMetadata|null $seoMetadata
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Testimonial> $testimonials
 * @property-read int|null $testimonials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketType> $ticketTypes
 * @property-read int|null $ticket_types_count
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event draft()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event past()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereGooglePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSalesEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSalesStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSeatingEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTotalCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVenueName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $target_audience
 * @property string|null $key_activities
 * @property string|null $ai_tone_style
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $rules
 * @property-read int|null $rules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereAiToneStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereKeyActivities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTargetAudience($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-write mixed $slug
 * @method static \Database\Factories\EventCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCategory query()
 * @mixin \Eloquent
 */
	class EventCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $event_id
 * @property string $title
 * @property string|null $description
 * @property string|null $event_category
 * @property int|null $target_audience_size
 * @property string|null $target_audience_description
 * @property numeric|null $budget_target
 * @property numeric|null $revenue_target
 * @property \Illuminate\Support\Carbon|null $event_date
 * @property string|null $location
 * @property string $status
 * @property string|null $ai_concept_result
 * @property string $concept_status
 * @property string|null $theme
 * @property string|null $tagline
 * @property string|null $narrative_summary
 * @property \Illuminate\Support\Carbon|null $concept_synced_at
 * @property array<array-key, mixed>|null $ai_budget_result
 * @property array<array-key, mixed>|null $ai_pricing_result
 * @property array<array-key, mixed>|null $ai_risk_result
 * @property string|null $notes
 * @property int $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Event|null $event
 * @property-read float $actual_net_profit
 * @property-read float $planned_net_profit
 * @property-read float $total_actual_expenses
 * @property-read float $total_actual_revenue
 * @property-read float $total_confirmed_talent_fees
 * @property-read float $total_planned_expenses
 * @property-read float $total_planned_revenue
 * @property-read float $total_planned_talent_fees
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanLineItem> $lineItems
 * @property-read int|null $line_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanRundown> $rundowns
 * @property-read int|null $rundowns_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanTalent> $talents
 * @property-read int|null $talents_count
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan archived()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan draft()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiBudgetResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiConceptResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiPricingResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiRiskResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereBudgetTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereConceptStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereConceptSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereEventCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereNarrativeSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereRevenueTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTargetAudienceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTargetAudienceSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan withoutTrashed()
 */
	class EventPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_plan_id
 * @property string $category
 * @property string|null $description
 * @property string $type
 * @property numeric $planned_amount
 * @property numeric|null $actual_amount
 * @property string|null $notes
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\EventPlan $eventPlan
 * @property-read float $variance
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem expense()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem revenue()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereEventPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem wherePlannedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem withoutTrashed()
 */
	class EventPlanLineItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_plan_id
 * @property string $title
 * @property string|null $description
 * @property string $start_time
 * @property string $end_time
 * @property string $type
 * @property int|null $event_plan_talent_id
 * @property string|null $notes
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\EventPlan $eventPlan
 * @property-read int $duration_minutes
 * @property-read string $type_color
 * @property-read \App\Models\EventPlanTalent|null $talent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereEventPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereEventPlanTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereUpdatedAt($value)
 */
	class EventPlanRundown extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\EventPlanLineItem|null $budgetLineItem
 * @property-read \App\Models\EventPlan|null $eventPlan
 * @property-read string $contract_status_color
 * @property-read \App\Models\Performer|null $performer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanTalent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanTalent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanTalent query()
 */
	class EventPlanTalent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @method static \Database\Factories\EventTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTag query()
 * @mixin \Eloquent
 */
	class EventTag extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $category_id
 * @property string $question
 * @property string $answer
 * @property int $order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\FaqCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq byCategory(?int $categoryId)
 * @method static \Database\Factories\FaqFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq search(string $search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq withoutTrashed()
 * @mixin \Eloquent
 */
	class Faq extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Faq> $faqs
 * @property-read int|null $faqs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory active()
 * @method static \Database\Factories\FaqCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory withoutTrashed()
 * @mixin \Eloquent
 */
	class FaqCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUserId($value)
 * @mixin \Eloquent
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $fileable_type
 * @property int $fileable_id
 * @property string $bucket_type
 * @property array<array-key, mixed>|null $collection
 * @property string $original_filename
 * @property string $stored_filename
 * @property string $file_path
 * @property string $url
 * @property string $mime_type
 * @property int $file_size
 * @property int|null $width
 * @property int|null $height
 * @property array<array-key, mixed>|null $metadata
 * @property array<array-key, mixed>|null $sizes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read Model|\Eloquent $fileable
 * @property-read string $formatted_size
 * @property-read bool $is_image
 * @method static Builder<static>|FileBucket documents()
 * @method static \Database\Factories\FileBucketFactory factory($count = null, $state = [])
 * @method static Builder<static>|FileBucket images()
 * @method static Builder<static>|FileBucket newModelQuery()
 * @method static Builder<static>|FileBucket newQuery()
 * @method static Builder<static>|FileBucket ofType(string $bucketType)
 * @method static Builder<static>|FileBucket onlyTrashed()
 * @method static Builder<static>|FileBucket query()
 * @method static Builder<static>|FileBucket whereBucketType($value)
 * @method static Builder<static>|FileBucket whereCollection($value)
 * @method static Builder<static>|FileBucket whereCreatedAt($value)
 * @method static Builder<static>|FileBucket whereCreatedBy($value)
 * @method static Builder<static>|FileBucket whereDeletedAt($value)
 * @method static Builder<static>|FileBucket whereFilePath($value)
 * @method static Builder<static>|FileBucket whereFileSize($value)
 * @method static Builder<static>|FileBucket whereFileableId($value)
 * @method static Builder<static>|FileBucket whereFileableType($value)
 * @method static Builder<static>|FileBucket whereHeight($value)
 * @method static Builder<static>|FileBucket whereId($value)
 * @method static Builder<static>|FileBucket whereMetadata($value)
 * @method static Builder<static>|FileBucket whereMimeType($value)
 * @method static Builder<static>|FileBucket whereOriginalFilename($value)
 * @method static Builder<static>|FileBucket whereSizes($value)
 * @method static Builder<static>|FileBucket whereStoredFilename($value)
 * @method static Builder<static>|FileBucket whereUpdatedAt($value)
 * @method static Builder<static>|FileBucket whereUrl($value)
 * @method static Builder<static>|FileBucket whereWidth($value)
 * @method static Builder<static>|FileBucket withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|FileBucket withoutTrashed()
 * @mixin \Eloquent
 * @property string $ulid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereUlid($value)
 */
	class FileBucket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $uuid
 * @property string $order_number
 * @property int $user_id
 * @property int $event_id
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $discount_amount
 * @property numeric $tax_amount
 * @property numeric $total_amount
 * @property string|null $notes
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileBucket> $files
 * @property-read int|null $files_count
 * @property-read bool $can_be_verified
 * @property-read bool $is_completed
 * @property-read bool $is_expired
 * @property-read bool $is_pending_payment
 * @property-read bool $is_pending_verification
 * @property-read int $total_tickets
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\PaymentProof|null $paymentProof
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order expired()
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order pendingPayment()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order pendingVerification()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withoutTrashed()
 * @mixin \Eloquent
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $ticket_type_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\TicketType $ticketType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Database\Factories\OrderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTicketTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property array<array-key, mixed>|null $social_media
 * @property string|null $address
 * @property int|null $logo_file_bucket_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\FileBucket|null $logo
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereLogoFileBucketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereSocialMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer withoutTrashed()
 * @mixin \Eloquent
 */
	class Organizer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $bank_name
 * @property string $account_number
 * @property string $account_holder
 * @property string|null $qr_code_path
 * @property string|null $logo_path
 * @property bool $is_active
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank active()
 * @method static \Database\Factories\PaymentBankFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereAccountHolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereQrCodePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank withoutTrashed()
 * @mixin \Eloquent
 */
	class PaymentBank extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $payment_method
 * @property string $content
 * @property bool $is_active
 * @property string $locale
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction byLocale(string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction byMethod(string $method)
 * @method static \Database\Factories\PaymentInstructionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction withoutTrashed()
 * @mixin \Eloquent
 */
	class PaymentInstruction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $file_bucket_id
 * @property string $status
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FileBucket $fileBucket
 * @property-read bool $is_approved
 * @property-read bool $is_pending
 * @property-read bool $is_rejected
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\User|null $verifier
 * @method static \Database\Factories\PaymentProofFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereFileBucketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentProof whereVerifiedBy($value)
 * @mixin \Eloquent
 */
	class PaymentProof extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $phone
 * @property int|null $photo_file_bucket_id
 * @property int|null $type_setting_component_id
 * @property int|null $profession_setting_component_id
 * @property bool $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\FileBucket|null $photo
 * @property-read \App\Models\SettingComponent|null $profession
 * @property-read \App\Models\SettingComponent|null $type
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer wherePhotoFileBucketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereProfessionSettingComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereTypeSettingComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanTalent> $eventPlanTalents
 * @property-read int|null $event_plan_talents_count
 */
	class Performer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $message
 * @property \Illuminate\Support\Carbon $target_date
 * @property string|null $url
 * @property bool $is_active
 * @property string $display_location
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown active()
 * @method static \Database\Factories\PromoCountdownFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown forLocation(string $location)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereDisplayLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereTargetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown withoutTrashed()
 * @mixin \Eloquent
 */
	class PromoCountdown extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $page_type
 * @property int $page_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $og_image
 * @property string|null $canonical_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Model|\Eloquent $page
 * @method static \Database\Factories\SeoMetadataFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata forPage(string $type, int $id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereCanonicalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata wherePageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class SeoMetadata extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $components
 * @property-read int|null $components_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Database\Factories\SettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withoutTrashed()
 * @mixin \Eloquent
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $setting_id
 * @property string $name
 * @property string $type
 * @property string|null $value
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property mixed $typed_value
 * @property-read \App\Models\Setting $setting
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Database\Factories\SettingComponentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent withoutTrashed()
 * @mixin \Eloquent
 */
	class SettingComponent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $template_type
 * @property int $template_id
 * @property string $content
 * @property int $version
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\EmailTemplate|null $emailTemplate
 * @property-read \App\Models\WhatsappTemplate|null $whatsappTemplate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereTemplateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereVersion($value)
 * @mixin \Eloquent
 */
	class TemplateVersion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property string $content
 * @property int $rating
 * @property string $status
 * @property bool $is_published
 * @property bool $is_featured
 * @property string|null $image_path
 * @property string|null $image_original_name
 * @property string|null $image_mime_type
 * @property int|null $image_width
 * @property int|null $image_height
 * @property int|null $image_file_size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read int $helpful_votes_count
 * @property-read int $not_helpful_votes_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestimonialVote> $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial featured()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereIsPublished($value)
 */
	class Testimonial extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $testimonial_id
 * @property int $user_id
 * @property bool $is_helpful
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Testimonial $testimonial
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereIsHelpful($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereTestimonialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereUserId($value)
 * @mixin \Eloquent
 */
	class TestimonialVote extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ulid
 * @property int $order_item_id
 * @property int $ticket_type_id
 * @property string $ticket_number
 * @property string|null $holder_name
 * @property string $status
 * @property string|null $qr_code_content
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_active
 * @property-read bool $is_cancelled
 * @property-read bool $is_used
 * @property-read mixed $order
 * @property-read \App\Models\OrderItem $orderItem
 * @property-read \App\Models\TicketType $ticketType
 * @method static \Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCheckedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereQrCodeContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string|null $name
 * @property string|null $description
 * @property numeric $price
 * @property int $quantity
 * @property int $sold_count
 * @property int $held_count
 * @property int $min_purchase
 * @property int $max_purchase
 * @property \Illuminate\Support\Carbon|null $sales_start_at
 * @property \Illuminate\Support\Carbon|null $sales_end_at
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read int $available_count
 * @property-read bool $is_available_for_sale
 * @property-read bool $is_sold_out
 * @property-read int $real_available_count
 * @method static \Database\Factories\TicketTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereHeldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereMaxPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereMinPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSalesEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSalesStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class TicketType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $identity_number
 * @property string|null $mobile_phone_number
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property bool $is_active
 * @property string $password
 * @property string|null $avatar
 * @property string|null $google_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $email_notifications
 * @property array<array-key, mixed>|null $in_app_notifications
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read int $unread_notifications_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestimonialVote> $testimonialVotes
 * @property-read int|null $testimonial_votes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Testimonial> $testimonials
 * @property-read int|null $testimonials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdentityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereInAppNotifications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMobilePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 * @property array<array-key, mixed>|null $privacy_settings
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePrivacySettings($value)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $content
 * @property array<array-key, mixed>|null $variables
 * @property string $locale
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TemplateVersion> $versions
 * @property-read int|null $versions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate byKey(string $key)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate byLocale(string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate default()
 * @method static \Database\Factories\WhatsappTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereVariables($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate withoutTrashed()
 * @mixin \Eloquent
 */
	class WhatsappTemplate extends \Eloquent {}
}

