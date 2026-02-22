<?php

return [
    // Section title
    'title' => 'Event Rundown',
    'section_title' => 'Schedule & Agenda',

    // Column labels
    'columns' => [
        'sort_order' => '#',
        'title' => 'Title',
        'time_range' => 'Time',
        'duration' => 'Duration',
        'type' => 'Type',
        'talent' => 'Performer',
        'description' => 'Description',
        'notes' => 'Notes',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
    ],

    // Form placeholders
    'placeholders' => [
        'title' => 'e.g. Opening Ceremony',
        'description' => 'Brief description of this segment...',
        'start_time' => 'e.g. 19:00',
        'end_time' => 'e.g. 19:30',
        'type' => 'Select type',
        'talent' => 'Link to a performer (optional)',
        'notes' => 'Production notes...',
        'sort_order' => 'e.g. 1',
    ],

    // Rundown types
    'types' => [
        'ceremony' => 'Ceremony',
        'performance' => 'Performance',
        'break' => 'Break',
        'setup' => 'Setup',
        'networking' => 'Networking',
        'registration' => 'Registration',
        'other' => 'Other',
    ],

    // Actions
    'actions' => [
        'add' => 'Add Rundown Item',
        'edit' => 'Edit Item',
        'delete' => 'Delete Item',
        'generate_ai' => 'AI Rundown Generator',
        'apply_ai' => 'Apply to Rundown',
    ],

    // Messages
    'messages' => [
        'time_overlap_warning' => 'Time overlap detected with ":item"',
        'ai_applied' => ':count rundown items added from AI suggestion.',
        'deleted' => 'Rundown item deleted.',
    ],

    // Empty state
    'empty_state' => [
        'heading'     => 'No rundown items yet',
        'description' => 'Add agenda items or use the AI Rundown Generator to get started.',
    ],

    // Widget specific
    'no_rundown'        => 'No rundown items yet.',
    'timeline_heading'  => 'Event Rundown Timeline',
    'time_range'        => 'Time',
    'title'             => 'Title',
    'type'              => 'Type',
    'validation' => [
        'missing_category'      => 'Please fill in Event Category first.',
        'missing_audience_size' => 'Please fill in Target Audience Size first.',
    ],

    // AI Modal
    'ai_modal' => [
        'title'          => 'AI Rundown Generator',
        'description'    => 'AI will generate a suggested event agenda based on your plan details and confirmed performers.',
        'preview_label'  => 'Generated Rundown Preview',
        'generating'     => 'Generating rundown...',
    ],
];
