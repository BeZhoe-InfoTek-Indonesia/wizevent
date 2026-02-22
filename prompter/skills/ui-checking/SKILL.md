---
name: ui-checking
description: Expert visual and structural validation for Skeuomorphism 2.0 (Laravel/Livewire). Use when implementing designs, fixing responsiveness, or reviewing component consistency.
---

# UI Checking & Validation

## Quick Start
Trigger this skill whenever you are:
- **Starting a new UI component** (to ensure design system compliance).
- **Fixing layout issues or responsiveness** (to follow mobile-first patterns).
- **Reviewing a pull request** (to catch design debt).

## Design System Reference
Always consult [design-system.md](/docs/design-system.md) for current tokens.
- **Primary Color**: `#DC2626` (Red-600)
- **Core Elements**: `.glass-panel`, `.glass-btn`, `.extruded-block`, `.pill-card`.
- **Physics**: Elements must "press in" (inset shadows) or "lift up" (translate-y).

## Workflows

### 1. Visual Consistency Audit
Compare the implementation against the **Skeuomorphism 2.0** standards:
- [ ] **Lighting**: Top-left light source. Does the border reflect this (e.g., `border-white/70`)?
- [ ] **Depth**: Are elements correctly using `shadow-skeuo` (raised) or `shadow-skeuo-inset` (pressed)?
- [ ] **Glassmorphism**: Ensure `backdrop-blur-xl` (16px+) is used with a semi-transparent white border.
- [ ] **Typography**: Check for `text-shadow-sm` on headings for readability.

### 2. Responsiveness & Mobile-First
When fixing responsiveness:
- [ ] **Base Class**: Start with mobile-first classes (e.g., `w-full md:w-1/2`).
- [ ] **Container Padding**: Ensure `px-4 md:px-8` on main containers.
- [ ] **Touch Targets**: Buttons must be at least `h-12` on mobile (`h-14` preferred for tactile feel).
- [ ] **Layout Shifts**: Use `min-h-[value]` to prevent layout jumps during Livewire loads.

### 3. Livewire & Alpine.js Validation
- [ ] **wire:key**: Every item in a `@foreach` loop MUST have a unique `wire:key`.
- [ ] **Loading States**: Use `wire:loading` with `glass-panel` overlays or skeletal loaders.
- [ ] **Transitions**: Use Alpine.js `x-transition` for all modal/dropdown visibility changes.
- [ ] **Deferring**: Use `wire:model.live` only when necessary; prefer `wire:model` for form performance.

### 4. Accessibility (A11y)
- [ ] **Contrast**: Ensure Red-600 has white text.
- [ ] **ARIA**: Use `aria-expanded` on filter modals and dropdowns.
- [ ] **Focus States**: Every interactive element must have a visible `.focus-visible:ring-primary` state.

## Scripts
- **UI Audit**: Run `python prompter/skills/ui-checking/scripts/ui_audit.py <path_to_blade_file>` to scan for common Livewire and Skeuomorphic styling errors.

## Resource Links
- **Design Tokens**: `docs/design-system.md`
- **Tailwind Config**: `tailwind.config.js`
- **Global Styles**: `resources/css/app.css` (check for `.glass-btn` definitions)
