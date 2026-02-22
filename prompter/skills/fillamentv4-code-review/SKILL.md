---
name: fillamentv4-code-review
description: Specialized skill for reviewing Filament v4.x (with Livewire 3.x) admin panels in Laravel projects. Use to ensure code quality, UI/UX consistency, security, and adherence to Filament and project conventions.
---

# Filament v4 Code Review Skill

## Quick Start
- Trigger this skill with: "review filament code", "analyze filament resource", or "check admin panel code quality".
- By default, reviews all Filament resources, pages, widgets, and related Livewire components in `app/Filament/`.
- Optionally, specify a file, resource, or review focus (e.g., i18n, modals, security).

## Workflows

## Internationalization (i18n) Note
- Whenever you add a new language or translation key, always update the `lang/` folder accordingly.
- Ensure all translation keys exist in `lang/en/` (English is the primary reference) before adding to other languages.


### 1. Filament Resource Review
- Check for use of modals/slideovers for all create/edit actions (no separate pages)
- Ensure all form fields have placeholders and use translation functions (`__()`)
- Verify labels, placeholders, and messages exist in `lang/en/` (English is primary)
- Confirm resource navigation and permissions match project roles/permissions
- Check for explicit imports, sorted alphabetically, no unused imports
- Validate adherence to PSR-12 and project naming conventions
- **Money Field Best Practice:**
	- Always use `->money('IDR', locale: 'id')` for all input money fields
	- Always use `->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))` for displaying money values
	- Ensure all money fields are labeled and formatted consistently as IDR (Indonesian Rupiah)

### 2. Widget & Page Review
- Ensure dashboard widgets use project metrics and are permission-aware
- Check for responsive design and accessibility (focus indicators, ARIA labels)
- Validate all user-facing text is translatable

### 3. Security & Authorization
- Confirm all resources/pages are protected by appropriate policies/middleware
- No direct model access in views; use services or repositories
- No hardcoded credentials or sensitive data in code

### 4. UI/UX Consistency
- Use Filament's modal/slideOver patterns for all CRUD
- Ensure consistent use of color, spacing, and iconography
- All actions provide user feedback (toast, notification, etc.)

## Resources
- See [AGENTS.md](../../AGENTS.md) for project conventions and Filament best practices
- See [docs/admin-interface-guide.md](../../docs/admin-interface-guide.md) for UI/UX and workflow details
- See [docs/security-features.md](../../docs/security-features.md) for security requirements

---
- For advanced Filament form patterns, see [references/forms.md](references/forms.md) (if present)
- For custom scripts, see `scripts/` in this skill directory (if present)
