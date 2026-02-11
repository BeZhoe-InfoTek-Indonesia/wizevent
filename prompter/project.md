# Project Context

## Purpose
**Event Ticket Management System** is a comprehensive, enterprise-grade SaaS platform designed to revolutionize the entire event lifecycle. It facilitates digital ticketing, real-time venue access control, and advanced analytics for event organizers, venues, and entertainment professionals.

The goal is to eliminate manual processes, enhance attendee experience through digital wallets and interactive seat selection, and provide robust security via encrypted QR codes and role-based access control.

## Tech Stack
- **Framework**: Laravel 11.x
- **Frontend**: Livewire 4.x, Blade Templates
- **Styling**: Tailwind CSS 3.x
- **Interactivity**: Alpine.js
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Breeze + Sanctum, Google OAuth 2.0 (Socialite)
- **Authorization**: Spatie Laravel Permission
- **Key Libraries**:
    - `intervention/image` (Image processing)
    - `simplesoftwareio/simple-qrcode` (QR generation)
    - `dompdf` (Invoice generation)
    - `spatie/laravel-activitylog` (Audit logging)

## Project Conventions

### Code Style
- **PHP**: Follows Laravel standards (PSR-12).
- **Naming**: 
    - Models: Singular, PascalCase (e.g., `Event`, `Ticket`).
    - Controllers: `ResourceController` naming convention.
    - Database: Snake_case for columns and table names.
- **Frontend**: 
    - Blade directives for logic in views.
    - Livewire components for dynamic interactions (prefixed with `Livewire/`).
    - Tailwind utility classes for styling.

### Architecture Patterns
- **Service Layer**: Business logic is encapsulated in services (e.g., `AuthService`, `PaymentService`, `QRCodeService`) to keep controllers thin.
- **Livewire Components**: Used for the Visitor Portal to provide an SPA-like experience.
- **Blade Templates**: Used for the Admin Panel for form-heavy CRUD operations.
- **Queues**: Database driver used for async jobs (emails, notifications).
- **Policies**: Model-based policies for authorization checks.

### Testing Strategy
- **Unit/Feature Tests**: PHPUnit/Pest (default Laravel stack).
- **Validation**:
    - **Security**: SQL injection prevention, XSS protection, and CSRF tokens.
    - **QR Code**: Cryptographic verification (AES-256 + HMAC-SHA256) for ticket validity.

### Git Workflow
- Standard feature-branch workflow.
- Commits should be descriptive and atomic.

## Domain Context
- **Event Lifecycle**: Draft -> Scheduled -> Active -> Sold Out -> Past/Cancelled.
- **Ticketing**: 
    - Digital tickets with encrypted QR codes.
    - Real-time inventory management to prevent overselling.
    - 10-minute seat hold mechanism during purchase.
- **User Roles**:
    - **Super Admin**: Full system control.
    - **Event Manager**: Event-specific management.
    - **Finance Admin**: Payments and refunds.
    - **Check-in Staff**: Scanning and entry validation.
    - **Visitor**: End-users purchasing tickets.

## Important Constraints
- **Mobile-First**: The visitor interface must be fully responsive and work well on mobile devices (PWA).
- **Performance**: Ticket validation (QR scan) must be sub-second.
- **Security**: 
    - Strict validation of payment proofs.
    - Anti-fraud measures for tickets (one-time use tokens).
- **Offline Access**: Visitors should be able to access tickets offline via service workers.

## External Dependencies
- **Google OAuth**: For visitor registration/login.
- **Payment Gateways**: Currently manual verification; Stripe integration is a high-priority roadmap item.
- **Storage**: Local disk (`storage/app/public`) initially; S3 compatible for future scaling.
