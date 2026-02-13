---
name: laravel-11-code-review
description: Comprehensive skill for reviewing Laravel 11 codebases (with Livewire 3.6.4+ and Filament 4.7+) for code quality, security, architecture, and adherence to project conventions. Use when you need actionable feedback on Laravel, Livewire, or Filament code.
---

# Laravel 11 Code Review Skill

## Quick Start
- Trigger with: "review code", "run code review", "analyze this file", or similar.
- By default, reviews all code in `app/` and `resources/views/`.
- Optionally, specify a file, directory, or review focus (e.g., security, i18n, service layer).

## Workflows

### 8. Preventing N+1 Query Problems
- Always eager load relationships in queries using `with()` or `load()` when accessing related models in loops or views.
- Avoid accessing relationships in Blade or Livewire views without eager loading.
- Use Laravel Debugbar or query log to detect N+1 issues during development.
- In service and repository classes, ensure all required relationships are loaded before returning data to controllers or views.
- Prefer `$model->load(['relation1', 'relation2'])` for existing models, and `Model::with(['relation1', 'relation2'])->get()` for collections.
- Add tests or code review checks to catch N+1 patterns in new code.

### 1. Architecture & Service Layer
- Ensure business logic is in service classes (`app/Services/`), not controllers
- Controllers should only parse requests and return responses
- Use fat model, skinny controller pattern
- Service classes must use strict type hinting and return types

### 2. Code Style & Naming
- Enforce PSR-12
- Naming conventions:
    - `camelCase` for methods
    - `snake_case` for variables, properties, DB columns
    - `PascalCase` for classes
- Imports:
    - Explicit `use` at top of file
    - Alphabetically sorted
    - No unused imports
    - No inline namespaces or FQCNs in code

### 3. Documentation & IDE Support
- All methods must have docblocks (`@param`, `@return`)
- Models must have PHPDoc blocks for IDE helper compatibility

### 4. Filament & Livewire
- All CRUD actions use modals/slideovers (not separate pages)
- All form fields have placeholders and use translation functions (`__()`)
- English (`lang/en/`) is the primary and reference language
- No hardcoded user-facing text

### 5. Security & Privacy
- No hardcoded credentials or sensitive data
- All resources/pages protected by policies/middleware
- No direct model access in views; use services or repositories
- Use CSRF tokens in all forms

### 6. Internationalization (i18n)
- All user-facing text uses translation functions
- All translation keys exist in `lang/en/` before other languages
- No mixed-language or hardcoded text in code

### 7. Forbidden Patterns
- No use of `RefreshDatabase` in tests
- No direct DB access in views
- No static service methods (except factory methods)

## Resources
- See [AGENTS.md](../../AGENTS.md) for project conventions and best practices
- See [docs/permissions-and-roles.md](../../docs/permissions-and-roles.md) for RBAC details
- See [docs/security-features.md](../../docs/security-features.md) for security requirements

---
- For advanced service layer patterns, see [references/service-layer.md](references/service-layer.md) (if present)
- For custom scripts, see `scripts/` in this skill directory (if present)
