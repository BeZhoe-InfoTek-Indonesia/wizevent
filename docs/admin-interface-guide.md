# Admin Interface User Guide

## Introduction
The Admin Interface allows authorized users to manage the Event Management System. Access is controlled via the Permission System.

## User Management
**Path:** `/admin/users`

- **List Users:** View all registered users with search and filtering by role via the dashboard.
- **Edit User:** Update user details (Name, Email).
- **Assign Roles:**
    1. Navigate to User List.
    2. Click "Assign Role" or "Edit" on a user.
    3. Select the desired role(s) (e.g., Event Manager).
    4. Save changes.
- **Bulk Actions:** Select multiple users to delete or update roles in bulk.

## Role Management
**Path:** `/admin/roles`

- **Create Role:** Define a new role (e.g., "Marketing Staff").
- **Edit Role:** Rename roles.
- **Manage Permissions:**
    1. Click "Permissions" on a role card.
    2. Toggle permissions using the matrix view.
    3. Save.
- **Permission Matrix:** View a grid of all roles and permissions for a quick overview.

## Permission Management
**Path:** `/admin/permissions`

- **View Permissions:** List all available system permissions.
- **Note:** Permissions are generally defined in the codebase, but can be managed by Super Admin if needed.

## Activity Logs
**Path:** `/admin/activity`

- **View Logs:** detailed history of user actions (Login, Create Event, Assign Role, etc.).
- **Filtering:** Filter by User, Action Type, or Date Range.
- **Details:** Click to view "Before" and "After" data for auditing changes.

## Profile Management
Every user key access their profile at `/profile`.

- **Update Profile:** Change Name, Email.
- **Avatar:** Upload a profile picture.
- **Security:**
    - Change Password.
    - View active login sessions.
    - Logout from other devices.
    - Delete Account (Caution: Permanent).
