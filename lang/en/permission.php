<?php

return [
    'placeholders' => [
        'name' => 'Enter permission name (e.g., view_users, create_posts)',
        'roles' => 'Select roles',
        'search_permissions' => 'Search permissions...',
    ],
    'helper_text' => [
        'name' => 'Use lowercase with underscores (snake_case)',
    ],
    'matrix' => [
        'title' => 'Permission Matrix',
        'description' => 'Manage role-based access control and system permissions across your organization.',
        'audit_logs' => 'Audit Logs',
        'permission_name' => 'Permission Name',
        'select_all' => 'SELECT ALL',
        'toggle_row_all' => 'TOGGLE ROW ALL',
    ],
    'categories' => [
        'events' => 'Events',
        'tickets' => 'Tickets',
        'users' => 'Users',
        'finance' => 'Finance',
        'system' => 'System',
        'permissions' => 'Permissions',
        'roles' => 'Roles',
    ],
    'notifications' => [
        'permission_updated' => 'Permission updated',
        'permissions_synced' => 'Permissions synced for :role',
        'group_updated' => 'Group \':group\' updated for all roles',
    ],
    'tooltips' => [
        'select_all' => 'Select all permissions for :role',
        'toggle_row_all' => 'Toggle all permissions in this group for all roles',
    ],
    'buttons' => [
        'clear' => 'Clear',
    ],
    'messages' => [
        'searching' => 'Searching permissions...',
        'no_permissions_found' => 'No permissions found matching your search.',
    ],
];
