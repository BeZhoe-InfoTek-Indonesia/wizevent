<?php

return [
    // Action labels
    'action_label'         => 'Sync to Live',
    'create_event_label'   => 'Create Event from Plan',
    'create_from_plan'     => 'Create Event from Plan',
    'confirm_label'        => 'Sync',
    'confirm_sync'         => 'Confirm Sync',
    'cancel'               => 'Cancel',

    // Modal headings
    'modal_heading'             => 'Sync Plan to Live Event',
    'create_event_heading'      => 'Create Event from Plan',
    'create_event_description'  => 'This will create a new draft Event using this plan\'s title, date, and location. The plan will be linked to the new event.',
    'modal_title'               => 'Sync Plan to Live Event',
    'diff_modal_title'          => 'Preview Changes',
    'create_event_modal_title'  => 'Create Event from Plan',

    // Diff helpers
    'has_changes'       => 'Changes detected.',
    'no_changes'        => 'No changes from current event.',
    'no_changes_found'  => 'No differences found between plan and live event.',

    // Notifications
    'success'                => 'Sync complete.',
    'no_sections_selected'   => 'Please select at least one section to sync.',
    'create_event_success'   => 'Event ":title" created and linked to this plan.',

    // Section titles for diff preview
    'sections' => [
        'concept'    => 'Concept & Description',
        'metadata'   => 'Event Metadata',
        'performers' => 'Performers',
        'tickets'    => 'Ticket Types',
        'rundown'    => 'Rundown / Agenda',
    ],

    // Section checkboxes
    'sync_sections' => [
        'concept'    => 'Sync event description from plan concept',
        'metadata'   => 'Sync title, date, and location',
        'performers' => 'Sync confirmed performers',
        'tickets'    => 'Sync ticket types from pricing strategy',
        'rundown'    => 'Sync rundown items',
    ],

    // Diff status labels
    'diff' => [
        'added'     => 'New',
        'updated'   => 'Changed',
        'unchanged' => 'Unchanged',
        'skipped'   => 'Skipped',
        'summary'   => ':create to create, :update to update, :skip unchanged',
    ],

    // Warnings
    'warnings' => [
        'no_event_linked'       => 'Link this plan to an event first, or create a new event from this plan.',
        'concept_overwrite'     => 'Event description was modified after last sync. Syncing will overwrite those changes.',
        'published_event'       => 'This event is published. Some changes may affect live visitors.',
        'no_selected_scenario'  => 'No pricing scenario selected. Please select a scenario in Pricing Strategy before syncing tickets.',
    ],

    // Validation messages
    'validation' => [
        'no_linked_event'       => 'The plan must be linked to an event before syncing.',
        'missing_title'         => 'The plan must have a title.',
        'plan_must_have_event'  => 'The plan must be linked to an event before syncing.',
        'permission_denied'     => 'You do not have permission to edit the target event.',
        'invalid_status'        => 'Only draft or active plans can be synced.',
        'plan_missing_title'    => 'The plan must have a title to create an event.',
        'plan_missing_date'     => 'The plan must have an event date to create an event.',
        'plan_missing_location' => 'The plan must have a location to create an event.',
    ],

    // Success messages (nested - kept for backward compat)
    'success_messages' => [
        'synced'         => 'Successfully synced :sections to event.',
        'event_created'  => 'Event created and linked to this plan.',
    ],

    // History section
    'history' => [
        'title'           => 'Sync History',
        'no_syncs'        => 'No syncs recorded yet.',
        'synced_at'       => 'Synced at',
        'synced_by'       => 'Synced by',
        'sections_synced' => 'Sections synced',
        'target_event'    => 'Target Event',
    ],

    // Acknowledge conflict checkbox
    'acknowledge_overwrite' => 'I understand this will overwrite manual edits made to the live event.',
];
