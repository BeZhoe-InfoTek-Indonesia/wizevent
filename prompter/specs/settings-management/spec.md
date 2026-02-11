# settings-management Specification

## Purpose
TBD - created by archiving change add-settings-management. Update Purpose after archive.
## Requirements
### Requirement: Settings Management
The system SHALL provide a facility for administrators strictly within the **Admin Panel** to manage application settings and their associated components.

#### Scenario: Create a new setting
- **WHEN** an administrator creates a new setting entry in the admin interface
- **THEN** a new row is added to the `settings` table
- **AND** the `key` must be unique across all settings
- **AND** `created_by` is set to the current user's ID
- **AND** the setting is successfully saved

#### Scenario: Retrieve all settings with components
- **WHEN** the system requests all current settings via the `SettingService` or relevant API
- **THEN** it returns a collection of `Setting` objects
- **AND** each setting includes its associated `SettingComponent` children
- **AND** invalid or soft-deleted items are excluded by default

### Requirement: Setting Component Management
The system SHALL allow administrators to define strongly-typed configuration values (components) under a parent setting.

#### Scenario: Add a boolean component
- **WHEN** an administrator adds a component with `type`="boolean" and `value`="true"
- **THEN** the value is stored as "true" (string) in `setting_components`
- **AND** the application correctly interprets it as a boolean when retrieved
- **AND** only valid boolean strings ("true", "false", "1", "0") are accepted

#### Scenario: Add an integer component
- **WHEN** an administrator adds a component with `type`="int" and `value`="123"
- **THEN** the application validates that "123" is a valid integer
- **AND** future retrieval returns the value cast to an integer type
- **AND** non-numeric input is rejected during validation

#### Scenario: Delete a setting component
- **WHEN** an administrator deletes a component
- **THEN** the `deleted_at` timestamp is set (soft delete)
- **AND** the component is no longer included in standard retrieval queries
- **AND** the `deleted_by` column (if applicable/tracked) is updated

