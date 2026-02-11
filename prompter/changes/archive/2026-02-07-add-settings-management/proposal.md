# Change: Add Settings Management

## Why
Currently, the system lacks a centralized way to manage dynamic configurations (e.g., site name, contact email, feature flags) without deploying code changes. Administrators need a flexible "Master Data" section to manage these settings and their components (values, types) directly from the admin panel.

## What Changes
- **Database**:
    - Create `settings` table for grouping configurations.
    - Create `setting_components` table for detailed key-value pairs with type enforcement.
- **Backend**:
    - Implement `Setting` and `SettingComponent` models.
    - Add Filament resources for managing Settings and Components.
- **API/Service**:
    - Expose methods/endpoints to retrieve settings and components.
    - Implement type validation for component values (e.g., ensuring a "boolean" type stores "true"/"false").

## Impact
- **Affected specs**: `settings-management` (new capability)
- **Affected code**: 
    - `database/migrations`
    - `app/Models/Setting.php`, `app/Models/SettingComponent.php`
    - `app/Filament/Resources/SettingResource.php`
