# Permissions and Roles Documentation

## Overview
The Event Management System uses a comprehensive Role-Based Access Control (RBAC) system powered by `spatie/laravel-permission`.

## Roles

The system is pre-configured with the following roles:

| Role | Description |
|------|-------------|
| **Super Admin** | Full access to all system features and settings. |
| **Event Manager** | Can view, create, edit, and publish events and manage tickets. |
| **Finance Admin** | Can view financial reports, verify payments, and process refunds. |
| **Check-in Staff** | Can view events and check in attendees. |
| **Visitor** | Can view events and book tickets. |

## Permissions

Permissions are categorized into 5 functional areas.

### Event Management (`events.*`)
- `events.create`: Create new events
- `events.edit`: Update existing events
- `events.delete`: Delete events
- `events.publish`: Publish events to make them visible
- `events.view`: View event details (admin view)

### Ticket Management (`tickets.*`)
- `tickets.create`: Create ticket types
- `tickets.edit`: Update ticket details
- `tickets.delete`: Delete ticket types
- `tickets.view`: View ticket information
- `tickets.check-in`: Process attendee check-ins

### User Management (`users.*`)
- `users.view`: View user list and details
- `users.edit`: Edit user profiles
- `users.delete`: Delete users
- `users.assign-roles`: Assign roles to users
- `users.manage-permissions`: Manage permission definitions and role assignments

### Finance Management (`finance.*`)
- `finance.view-reports`: Access financial reports
- `finance.verify-payments`: Verify manual payments
- `finance.process-refunds`: Process refund requests

### System Management (`system.*`)
- `system.manage-settings`: Configure system-wide settings
- `system.view-logs`: View activity and error logs

## Role-Permission Matrix

| Permission | Super Admin | Event Manager | Finance Admin | Check-in Staff | Visitor |
|------------|:-----------:|:-------------:|:-------------:|:--------------:|:-------:|
| **Events** |
| events.create | ✅ | ✅ | ❌ | ❌ | ❌ |
| events.edit | ✅ | ✅ | ❌ | ❌ | ❌ |
| events.publish | ✅ | ✅ | ❌ | ❌ | ❌ |
| events.view | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Tickets** |
| tickets.create | ✅ | ✅ | ❌ | ❌ | ✅ |
| tickets.edit | ✅ | ✅ | ❌ | ❌ | ❌ |
| tickets.view | ✅ | ✅ | ✅ | ✅ | ✅ |
| tickets.check-in | ✅ | ✅ | ❌ | ✅ | ❌ |
| **Users** |
| users.view | ✅ | ✅ | ❌ | ❌ | ❌ |
| users.edit | ✅ | ❌ | ❌ | ❌ | ❌ |
| users.assign-roles | ✅ | ❌ | ❌ | ❌ | ❌ |
| users.manage-permissions | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Finance** |
| finance.view-reports | ✅ | ❌ | ✅ | ❌ | ❌ |
| finance.verify-payments | ✅ | ❌ | ✅ | ❌ | ❌ |
| finance.process-refunds | ✅ | ❌ | ✅ | ❌ | ❌ |
| **System** |
| system.manage-settings | ✅ | ❌ | ❌ | ❌ | ❌ |
| system.view-logs | ✅ | ❌ | ❌ | ❌ | ❌ |

✅ = Has Permission
❌ = No Permission
