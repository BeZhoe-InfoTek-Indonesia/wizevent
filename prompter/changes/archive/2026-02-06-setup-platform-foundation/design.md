# Design: Platform Foundation Architecture

## Context
The Event Ticket Management System requires a scalable architecture that supports two distinct user groups: Administrators (staff/organizers) and Visitors (end-users). The system must handle high-concurrency ticket validation and provide a seamless booking experience.

## Goals / Non-Goals
- **Goals**:
    - Clean separation of concerns between Admin and Visitor interfaces.
    - Centralized business logic in a reusable Service Layer.
    - Type-safe code where possible (PHPStan Level 5).
    - Responsive, mobile-first design for Visitors.
- **Non-Goals**:
    - Microservices architecture (Monolith is sufficient).
    - Native mobile applications (PWA approach).
    - Complex caching strategies (Redis) for MVP.

## Decisions

### Decision: Service Layer Pattern
- **What**: Encapsulate business logic in `App\Services\` classes rather than Controllers or Models.
- **Why**: Keeps controllers thin, makes logic reusable (e.g., across Web/API/Console), and simplifies testing.
- **Example**: `EventService::create()` handles validation, slug generation, and storage, while the Controller just handles the HTTP request/response.

### Decision: Hybrid Frontend Architecture
- **What**: 
    - **Admin Panel**: Server-side rendered Blade templates + Alpine.js.
    - **Visitor Portal**: Livewire 4.x components.
- **Why**: 
    - Admin needs robust, simple CRUD forms (Blade is efficient).
    - Visitors need a highly interactive, SPA-like feel for booking/checkout (Livewire provides this without API complexity).

### Decision: Database-Driven Queues
- **What**: Use MySQL for queue management initially.
- **Why**: Reduces infrastructure complexity (no Redis dependency) for the MVP while staying scalable enough for moderate loads.

### Decision: Spatie Permissions for RBAC
- **What**: Use `spatie/laravel-permission`.
- **Why**: De-facto standard for Laravel, flexible role/permission management required for the multi-role system (Super Admin, Event Manager, Check-in Staff).

## Risks / Trade-offs
- **Risk**: Performance bottleneck with Database Queues.
    - **Mitigation**: Monitor job throughput; upgrade to Redis if load increases.
- **Risk**: "Fat" Services if not careful.
    - **Mitigation**: Enforce single-responsibility principle in code reviews.

## Open Questions
- None at this stage.
