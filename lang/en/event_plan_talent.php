<?php

return [
    // Section title
    'title' => 'Talent Management',
    'section_title' => 'Talent Lineup',

    // Column labels
    'columns' => [
        'performer' => 'Performer',
        'planned_fee' => 'Planned Fee',
        'actual_fee' => 'Actual Fee',
        'slot_time' => 'Slot Time',
        'duration' => 'Duration (min)',
        'contract_status' => 'Contract Status',
        'budget_linked' => 'Budget Linked',
        'performance_order' => 'Order',
        'rider_notes' => 'Rider Notes',
        'notes' => 'Notes',
    ],

    // Form placeholders
    'placeholders' => [
        'performer' => 'Search and select a performer...',
        'planned_fee' => 'e.g. 5000000',
        'actual_fee' => 'e.g. 5000000',
        'slot_time' => 'e.g. 19:00',
        'duration' => 'e.g. 60',
        'performance_order' => 'e.g. 1',
        'rider_notes' => 'List technical and hospitality requirements...',
        'notes' => 'Additional notes...',
        'contract_status' => 'Select status',
    ],

    // Contract status values
    'contract_statuses' => [
        'draft' => 'Draft',
        'negotiating' => 'Negotiating',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
    ],

    // Actions
    'actions' => [
        'add' => 'Add Talent',
        'edit' => 'Edit Talent',
        'delete' => 'Remove Talent',
        'link_to_budget' => 'Link to Budget',
        'unlink_from_budget' => 'Unlink from Budget',
    ],

    // Messages
    'messages' => [
        'already_assigned' => 'This performer is already assigned to this plan.',
        'budget_linked' => 'Talent fee linked to budget line item.',
        'budget_unlinked' => 'Budget link removed.',
        'deleted' => 'Talent removed from plan.',
        'fee_synced' => 'Budget line item updated with new fee.',
    ],

    // Empty state
    'empty_state' => [
        'heading' => 'No talents yet',
        'description' => 'Add performers to your event plan lineup.',
    ],

    // Summary
    'summary' => [
        'total_planned'      => 'Total Planned Fees',
        'total_confirmed'    => 'Total Confirmed Fees',
        'budget_utilization' => 'Talent Budget Utilization',
    ],

    // Status board widget
    'status_board' => [
        'no_talent'      => 'No Talents',
        'no_talent_hint' => 'Add performers to this plan to see the status board.',
        'none'           => '(none)',
    ],
];
