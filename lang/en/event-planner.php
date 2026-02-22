<?php

return [
    'title' => 'Event Planner',
    'description' => 'Plan events before publishing with AI-powered insights',

    'labels' => [
        'title' => 'Title',
        'event' => 'Linked Event',
        'event_category' => 'Event Category',
        'target_audience_size' => 'Target Audience Size',
        'target_audience_description' => 'Target Audience Description',
        'budget_target' => 'Budget Target',
        'revenue_target' => 'Revenue Target',
        'event_date' => 'Event Date',
        'location' => 'Location',
        'description' => 'Description',
        'notes' => 'Notes',
        'status' => 'Status',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        // Concept & Narrative fields
        'concept_status' => 'Concept Status',
        'theme' => 'Event Theme',
        'tagline' => 'Tagline',
        'narrative_summary' => 'Narrative Summary',
        'concept_synced_at' => 'Last Synced At',
    ],

    'placeholders' => [
        'title' => 'Enter event plan title',
        'event' => 'Select an event to link (optional)',
        'event_category' => 'e.g., Music Festival, Conference, Workshop',
        'target_audience_size' => 'e.g., 500',
        'target_audience_description' => 'Describe your target audience',
        'budget_target' => '0.00',
        'revenue_target' => '0.00',
        'event_date' => 'Select event date',
        'location' => 'e.g., Jakarta Convention Center',
        'description' => 'Describe your event plan',
        'notes' => 'Additional notes (internal)',
        // Concept & Narrative placeholders
        'theme' => 'e.g., Neon Night, Cultural Heritage, Futuristic',
        'tagline' => 'e.g., "Where music meets the soul"',
        'narrative_summary' => 'Briefly describe the event narrative and story arc...',
    ],

    'statuses' => [
        'draft' => 'Draft',
        'active' => 'Active',
        'completed' => 'Completed',
        'archived' => 'Archived',
    ],

    'concept_statuses' => [
        'brainstorm' => 'Brainstorm',
        'drafted' => 'Drafted',
        'finalized' => 'Finalized',
        'synced' => 'Synced',
    ],

    'filters' => [
        'has_linked_event' => 'Has Linked Event',
    ],

    'ai_actions' => [
        'demo_mode_warning' => 'Demo Mode — Configure AI keys for real results.',

        'concept_builder' => [
            'action_label'        => 'AI Concept Builder',
            'regenerate_label'    => 'Regenerate Concept',
            'apply_label'         => 'Apply to Event',
            'description'         => 'Generate an AI-crafted event concept based on your plan details.',
            'preview_title'       => 'AI-Generated Event Concept',
            'ai_result_label'     => 'AI-Generated Concept',
            'ai_result_helper'    => 'Review the AI-generated concept before applying it to your event.',
            'regenerate_confirm'  => 'This will replace the current AI concept. Continue?',
            'apply_confirm'       => 'This will overwrite the linked event\'s description. Continue?',
            'apply_success'       => 'Concept applied to event successfully.',
            'no_ai_result'        => 'No concept generated yet. Run AI Concept Builder first.',
            'no_linked_event'     => 'Link an event to this plan first to apply the concept.',
            'success'             => 'Concept generated successfully.',
        ],

        'budget_forecast' => [
            'action_label'        => 'AI Budget Forecast',
            'apply_label'         => 'Apply to Budget',
            'preview_title'       => 'AI Budget Forecast',
            'apply_confirm'       => 'This will add :count expense line items from the AI forecast. Continue?',
            'apply_success'       => ':count line items added from AI forecast.',
            'no_ai_result'        => 'No forecast generated yet. Run AI Budget Forecast first.',
            'success'             => 'Budget forecast generated successfully.',
            'ai_generated_note'   => 'AI-generated estimate',
            'validation' => [
                'missing_category'      => 'Please fill in Event Category first.',
                'missing_audience_size' => 'Please fill in Target Audience Size first.',
            ],
        ],

        'pricing_strategy' => [
            'action_label'           => 'AI Pricing Strategy',
            'apply_label'            => 'Apply to Event Tickets',
            'preview_title'          => 'AI Pricing Strategy',
            'apply_confirm'          => 'This will create :count new ticket types on the linked event. Existing ticket types will NOT be modified. Continue?',
            'apply_confirm_scenario' => 'This will create :count ticket types from the ":scenario" scenario. Existing ticket types will NOT be modified. Continue?',
            'apply_success'          => ':count ticket types created from AI pricing strategy.',
            'no_ai_result'           => 'No pricing strategy generated yet. Run AI Pricing Strategy first.',
            'no_linked_event'        => 'Link an event to this plan first to create ticket types.',
            'success'                => 'Pricing strategy generated successfully.',
            'target_met'             => 'Target Met',
            'target_not_met'         => 'Target Not Met',
            'surplus'                => 'Surplus: :amount',
            'deficit'                => 'Deficit: :amount',
            'validation' => [
                'missing_revenue_target' => 'Please fill in Revenue Target first.',
                'missing_audience_size'  => 'Please fill in Target Audience Size first.',
            ],
        ],

        'rundown_generator' => [
            'action_label'   => 'AI Rundown Generator',
            'modal_heading'  => 'AI-Generated Event Rundown',
            'apply_label'    => 'Append to Rundown',
            'apply_success'  => ':count rundown items added.',
            'empty'          => 'No rundown items generated. Fill in Event Category and Audience Size first.',
            'validation' => [
                'missing_category'      => 'Please fill in Event Category first.',
                'missing_audience_size' => 'Please fill in Target Audience Size first.',
            ],
        ],

        'risk_assessment' => [
            'action_label'  => 'AI Risk Assessment',
            'preview_title' => 'AI Risk Assessment',
            'success'       => 'Risk assessment completed.',
            'overall_score' => 'Overall Risk Score',
            'mitigation'    => 'Mitigation',
            'no_ai_result'  => 'No assessment generated yet. Run AI Risk Assessment first.',
        ],
    ],

    'planning_vs_realization' => [
        'title'                    => 'Planning vs Realization',
        'planned_revenue'          => 'Planned Revenue',
        'actual_revenue'           => 'Actual Revenue',
        'revenue_target'           => 'Revenue Target',
        'planned_expenses'         => 'Planned Expenses',
        'actual_expenses'          => 'Actual Expenses',
        'planned_net_profit'       => 'Planned Net Profit',
        'actual_net_profit'        => 'Actual Net Profit',
        'revenue_achievement_rate' => 'Revenue Achievement Rate',
        'budget_utilization_rate'  => 'Budget Utilization Rate',
        'net_margin'               => 'Net Margin',
        'tickets_sold_vs_target'   => 'Tickets Sold vs Target',
        'tickets_sold'             => 'Tickets Sold',
        'no_linked_event'          => 'No linked event — link an event to track revenue.',
        'no_data'                  => 'No data available yet.',
        'expense_by_category'      => 'Expenses by Category',
        'revenue_comparison'       => 'Revenue Comparison',
        'kpi_summary'              => 'KPI Summary',
    ],

    'line_items' => [
        'title' => 'Line Items',
        'labels' => [
            'type' => 'Type',
            'category' => 'Category',
            'description' => 'Description',
            'planned_amount' => 'Planned Amount',
            'actual_amount' => 'Actual Amount',
            'notes' => 'Notes',
            'sort_order' => 'Sort Order',
            'variance' => 'Variance',
        ],
        'placeholders' => [
            'category' => 'e.g., Venue, Marketing, Talent',
            'description' => 'Optional description',
            'notes' => 'Additional notes',
            'planned_amount' => '0.00',
            'actual_amount' => '0.00',
        ],
        'types' => [
            'expense' => 'Expense',
            'revenue' => 'Revenue',
        ],
    ],

    'action_groups' => [
        'concept'  => 'Concept',
        'budget'   => 'Budget',
        'pricing'  => 'Pricing',
        'rundown'  => 'Rundown',
        'sync'     => 'Sync & Deploy',
    ],

    // Concept & Narrative section
    'concept_narrative' => [
        'section_title' => 'Concept & Narrative',
        'mark_finalized' => 'Mark as Finalized',
        'mark_finalized_confirm' => 'Mark this concept as finalized? This cannot be undone.',
        'mark_finalized_success' => 'Concept marked as finalized.',
        'ai_concept_preview' => 'AI-Generated Concept',
        'no_concept_yet' => 'No concept generated yet.',
    ],

    // Sync to Live
    'sync_to_live' => 'Sync to Live',
    'create_event_from_plan' => 'Create Event from Plan',

    // Pricing scenarios
    'pricing_scenarios' => [
        'pessimistic'         => 'Pessimistic',
        'realistic'           => 'Realistic',
        'optimistic'          => 'Optimistic',
        'select_label'        => 'Select Scenario',
        'modal_heading'       => 'Select Pricing Scenario',
        'select_scenario'     => 'Use This Scenario',
        'selected_badge'      => 'Selected',
        'scenario_tab_label'  => ':name Scenario',
        'scenario_selected'   => '":scenario" set as selected scenario.',
        'select_confirm'      => 'Set ":scenario" as the selected scenario for ticket creation?',
        'select_success'      => '":scenario" scenario selected.',
        'no_scenario_selected' => 'No scenario selected. Use "Select Scenario" first.',
        'generated_hint'      => 'Review the 3 scenarios and select one before applying to event tickets.',
        'projected_revenue'   => 'Projected Revenue',
        'surplus_deficit'     => 'Surplus / Deficit',
        'target_met'          => 'Target Met',
        'target_not_met'      => 'Target Not Met',
        'tier_name'           => 'Tier',
        'price'               => 'Price',
        'quantity'            => 'Qty',
    ],

    // Monitoring dashboard
    'monitoring' => [
        'talent_confirmation_rate'  => 'Talent Confirmation Rate',
        'rundown_completeness'      => 'Rundown Completeness',
        'days_until_event'          => 'Days Until Event',
        'no_rundown'                => 'No Rundown',
        'rundown_ready'             => 'Rundown Ready',
        'unconfirmed_talent'        => 'Unconfirmed Talent',
        'over_budget'               => 'Over Budget',
        'low_revenue'               => 'Low Revenue',
        'event_day'                 => 'Event Day!',
        'days_left'                 => ':days days left',
        'past_event'                => 'Event Passed',
        'sales_phase_tracker'       => 'Sales Phase Tracker',
        'ticket_type'               => 'Ticket Type',
        'price'                     => 'Price',
        'sold'                      => 'Sold',
        'remaining'                 => 'Remaining',
        'fill_rate'                 => 'Fill Rate',
        'phase'                     => 'Phase',
        'no_ticket_types'           => 'No ticket types found for this event.',
        'confirmed_of'              => 'confirmed of :total',
        'items_in_rundown'          => ':count items in rundown',
    ],
];
