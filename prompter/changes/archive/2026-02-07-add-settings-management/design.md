# Design: Settings Management

## Context
The application requires a flexible "Master Data" module to manage system-wide settings dynamically. This avoids hardcoding values and allows admins to update configurations without deployment. The user requested specific schema details including `bpchar` for component IDs.

## Goals / Non-Goals
- **Goals**:
    - Centralized management of settings groups (`settings` table) and individual values (`setting_components`).
    - Strong typing for values (string, int, boolean) enforced at the application level.
    - Soft delete support for auditable changes.
    - Filament resource for easy management.
- **Non-Goals**:
    - Complex versioning of settings (history is handled via `deleted_at` only).
    - Specific UI for the frontend user (this is backend management).

## Decisions
- **Decision**: Use `char(26)` (ULID) or `char(36)` (UUID) for `setting_components.id` to satisfy the `bpchar` requirement in a MySQL context (mapping `bpchar` to `char` as MySQL lacks `bpchar`). Given the user is on MySQL, `char` is the closest equivalent.
- **Decision**: Store values as `varchar` (string) but cast them based on the `type` column in the model accessor/mutator. This allows a single column to store multiple data types.
- **Decision**: Implement a `SettingService` to encapuslate retrieval logic, making it easy to fetch structured settings.

## Risks / Trade-offs
- **Risk**: Storing everything as `varchar` means we lose database-level type safety for non-string values.
- **Mitigation**: Implement strict validation in the `SettingComponent` model and Filament forms to ensure `int` values are numeric and `boolean` values are true/false strings.

## Migration Plan
- Create migration files for `settings` and `setting_components`.
- Run migrations.
