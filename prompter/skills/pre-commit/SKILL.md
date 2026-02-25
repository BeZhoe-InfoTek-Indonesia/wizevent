---
name: pre-commit
description: >
  Comprehensive AI-assisted pre-commit review skill for the Event Ticket Management System.
  Checks every staged file for code quality, security, i18n compliance, N+1 risks, Blade
  best practices, and Filament v4 conventions before a commit is allowed.
  Trigger with: "pre-commit review", "check staged files", "review before commit", etc.
---

# Pre-Commit Review Skill

## Quick Start

Trigger this skill when:
- You are about to commit and want a full safety check
- Asking: "run pre-commit review", "check staged files", "is this safe to commit?"
- After making changes to PHP, Blade, lang, or JS files

**Default scope:** All files currently staged (`git diff --cached --name-only`).
**Custom scope:** Specify a directory or file pattern (e.g., "review only app/Filament/").

---

## Review Pipeline

Run **all stages** below in order. Report findings grouped by severity:
- 🔴 **BLOCKER** — Must fix before committing (will break app or fail CI)
- 🟡 **WARNING** — Should fix soon (technical debt, bad practice)
- 🟢 **PASS** — No issues found in this category

---

### Stage 1 — PHP Code Quality

**Files:** `app/**/*.php`, `config/**/*.php`, `database/**/*.php`

#### 1.1 Type Safety & PHPStan (Level 5)
- [ ] Every method has a **return type declaration** and **typed parameters**
- [ ] No `mixed` types unless absolutely unavoidable
- [ ] No calls to undefined methods on potentially-null variables
- [ ] No array shapes that PHPStan cannot infer — add `@param array{key: type}` docblock
- [ ] All `@param` and `@return` docblocks present on public methods
- **Auto-fix command:** `composer phpstan`

#### 1.2 PSR-12 Code Style
- [ ] No inline/FQCN class usage — use `use` imports at top of file
- [ ] Imports are alphabetically sorted with no unused entries
- [ ] `camelCase` methods, `snake_case` variables/properties, `PascalCase` classes
- [ ] No trailing whitespace or mixed indentation (tabs vs spaces)
- **Auto-fix command:** `./vendor/bin/pint`

#### 1.3 Architecture Compliance
- [ ] Controllers are thin — no business logic, only request parsing + response
- [ ] Complex logic lives in `app/Services/` classes
- [ ] No `Model::all()` or unbound queries in controllers or views
- [ ] No `DB::` facade calls directly in Blade templates
- [ ] Service classes are stateless (no `$this->state = ...` between calls)

#### 1.4 Security
- [ ] No hardcoded credentials, API keys, or secrets
- [ ] No raw SQL with user input — use Eloquent bindings or `DB::select()` with bindings
- [ ] No `eval()`, `exec()`, `shell_exec()`, `passthru()` with user-controlled input
- [ ] All Eloquent mass-assignment is guarded by `$fillable` or `$guarded`
- [ ] All file uploads validated for mime type and max size

---

### Stage 2 — Blade & Livewire Templates

**Files:** `resources/views/**/*.blade.php`

#### 2.1 Livewire Best Practices
- [ ] Every `@foreach` inside a Livewire component has `wire:key="unique-id"`
- [ ] No `wire:model.live` on fields that fire frequent updates (use `.debounce.300ms`)
- [ ] Livewire actions that modify DB are wrapped in `try/catch` with user feedback
- [ ] No Alpine.js `$el` or `$refs` accessing Livewire-rendered elements directly
- [ ] `wire:loading` states present on all buttons that trigger server calls

#### 2.2 XSS & Output Safety
- [ ] `{!! $var !!}` is only used for **trusted HTML** (sanitised rich-text fields)
- [ ] User-supplied content always uses `{{ $var }}` (auto-escaped)
- [ ] No inline `<script>` tags pulling from PHP variables without `json_encode()`

#### 2.3 N+1 Query Prevention
- [ ] Relationships accessed in Blade loops are **eager-loaded** in the component/controller
- [ ] Patterns like `@foreach($events) {{ $event->organizer->name }}` are red flags — check the query
- [ ] Collections used in views come from `with(['relation'])` or `->load('relation')`
- [ ] No `$model->relation` inside nested `@foreach` blocks without eager loading

#### 2.4 Design System Compliance (Skeuomorphism 2.0)
- [ ] Depth via `shadow-*` (raised) or `shadow-*-inset` (pressed) — no flat unshadowed buttons
- [ ] Touch targets ≥ `h-10` (min), `h-12` preferred on mobile forms
- [ ] Mobile-first Tailwind classes — base class for mobile, `md:` prefix for desktop
- [ ] Skeleton loaders used instead of spinners for initial data loads
- [ ] `wire:key` on slider/carousel items to prevent ghost re-renders

---

### Stage 3 — Internationalization (i18n)

**Files:** `resources/views/**/*.blade.php`, `app/**/*.php`, `lang/**/*.php`

#### 3.1 Translation Coverage
- [ ] ALL user-facing strings use `__('key')` or `trans('key')` — **no hardcoded English**
- [ ] Filament field labels, placeholders, table column headers all use `__('key')`
- [ ] Notification messages, flash alerts, and validation messages are translated
- [ ] No hardcoded text in `->label()`, `->placeholder()`, `->helperText()` Filament methods

#### 3.2 Key Management
- [ ] New translation keys added to `lang/en/*.php` **first** (English is PRIMARY)
- [ ] After adding to English, corresponding keys added to `lang/id/*.php`
- [ ] No orphaned keys in `lang/id/` that don't exist in `lang/en/`
- [ ] Parameterised translations used for dynamic content: `__('key', ['var' => $value])`

#### 3.3 Filament Forms i18n
- [ ] `TextInput::make()->label(__('...'))` — label translated
- [ ] `TextInput::make()->placeholder(__('...'))` — placeholder translated
- [ ] `Select::make()->options([])` — option labels translated if static
- [ ] Notification titles and bodies in `->success()/->danger()` use `__('...')`

---

### Stage 4 — Filament v4 Resources

**Files:** `app/Filament/**/*.php`

#### 4.1 Modal/Slideover Pattern
- [ ] Create/Edit actions use `->modal()` or `->slideOver()` — **never separate pages**
- [ ] Table row actions (`EditAction`, `DeleteAction`) use modals
- [ ] Header actions (`CreateAction`) use modals or slideOver
- [ ] `BulkAction` has a `->requiresConfirmation()` call

#### 4.2 Unified Action Class
- [ ] All actions import `Filament\Actions\Action` — **never** `Filament\Forms\Components\Actions\Action`
- [ ] Custom table actions use `Tables\Actions\Action` from `Filament\Tables`
- [ ] No mixing of action class imports within the same Resource file

#### 4.3 Permissions & Policies
- [ ] Every Resource has a corresponding Policy in `app/Policies/`
- [ ] `canAccess()`, `canViewAny()`, `canCreate()`, `canEdit()`, `canDelete()` are defined
- [ ] Super Admin permissions are tested with `Shield` or `Spatie`
- [ ] Resources not meant for all roles use `->navigationGroup()` with correct visibility

#### 4.4 Currency Fields (IDR Standard)
- [ ] Money input fields use `->money('IDR', locale: 'id')` with `->numeric()`
- [ ] Money display columns use `->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))`
- [ ] No raw number formatting that ignores IDR locale conventions

---

### Stage 5 — Service Layer

**Files:** `app/Services/**/*.php`

#### 5.1 Service Class Contracts
- [ ] Service methods return **Eloquent models or typed DTOs** — never raw arrays
- [ ] Multi-step DB operations are wrapped in `DB::transaction(fn () => ...)`
- [ ] All external API calls (AI, payment gateways) have `try/catch` with fallback
- [ ] AI service calls have a `getMocked*()` equivalent for local dev without API keys

#### 5.2 Job & Queue
- [ ] Heavy operations (PDF generation, QR code batch, email delivery) are dispatched to queue
- [ ] Jobs implement `ShouldQueue` and have a `$tries` and `$backoff` defined
- [ ] No synchronous mail sending — always use `Mail::queue()` or `->later()`

---

### Stage 6 — Migration & Database Safety

**Files:** `database/migrations/**/*.php`

#### 6.1 Migration Safety
- [ ] Migrations are **additive** — no column drops without a separate archival migration
- [ ] New nullable columns have a `->nullable()->default(null)` or sensible default
- [ ] Foreign keys have `->constrained()->cascadeOnDelete()` or `restrictOnDelete()` explicitly set
- [ ] No raw `Schema::dropColumn()` that could fail on production without `down()` guard

#### 6.2 Seeder Rules
- [ ] Seeders use `firstOrCreate()` or `updateOrCreate()` — **never truncate in test env**
- [ ] No `RefreshDatabase` trait in tests — use in-memory SQLite (already configured in `phpunit.xml`)

---

## Output Format

For each stage, report in this format:

```
## Stage N — [Name]
🔴 BLOCKER (N issues)
  - [file:line] Description of issue. Suggested fix.

🟡 WARNING (N issues)
  - [file:line] Description of issue. Suggested fix.

🟢 PASS — No issues found.
```

End with a **Commit Readiness Summary**:

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
COMMIT READINESS SUMMARY
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔴 BLOCKERS:  N  (must fix before commit)
🟡 WARNINGS:  N  (fix before push/PR)
🟢 PASSED:    N stages

Verdict: ✅ SAFE TO COMMIT  |  ❌ BLOCKED — Fix N issues first
```

---

## Auto-Fix Commands

When blockers are Pint or PHPStan fixable, suggest running:

```bash
# Fix code style
./vendor/bin/pint

# Check static analysis
composer phpstan

# Re-stage all fixed PHP files
git add $(git diff --name-only | grep '\.php$')
```

---

## Quick Modes

| Mode | Command phrase | Scope |
|------|----------------|-------|
| **Full review** | "pre-commit review" | All 6 stages |
| **PHP only** | "check PHP before commit" | Stages 1, 5, 6 |
| **Blade only** | "check Blade before commit" | Stage 2 |
| **i18n only** | "check translations before commit" | Stage 3 |
| **Filament only** | "check Filament before commit" | Stage 4 |
| **Security scan** | "security check before commit" | Stages 1.4, 2.2 |

---

## Resources

- [AGENTS.md](../../AGENTS.md) — Project conventions, coding rules, permission structure
- [docs/design-system.md](../../docs/design-system.md) — Skeuomorphism 2.0 tokens and components
- [docs/permissions-and-roles.md](../../docs/permissions-and-roles.md) — RBAC structure
- [lang/en/](../../lang/en/) — Primary translation files (always update first)
- [phpstan.neon](../../phpstan.neon) — PHPStan configuration (Level 5)
- [.husky/pre-commit](../../.husky/pre-commit) — Automated hook (Pint + PHPStan + guards)
- Related skills:
  - [`laravel-11-code-review`](../laravel-11-code-review/SKILL.md) — Deep Laravel review
  - [`filamentv4-code-review`](../fillamentv4-code-review/SKILL.md) — Filament-specific review
  - [`ui-checking`](../ui-checking/SKILL.md) — Visual & design system audit
