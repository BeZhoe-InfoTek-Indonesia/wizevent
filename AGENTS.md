## 19. 🧩 Project Skills: Automated Code Review

This project includes custom AI skills for automated code review and best practices enforcement:

- **Laravel 11 Code Review Skill** (`laravel-11-code-review`)
  - Location: `prompter/skills/laravel-11-code-review/SKILL.md`
  - Use for: General Laravel, Livewire, and service layer code review, security, i18n, and N+1 query prevention.
  - Trigger by asking: "Review code for Laravel 11 best practices", "Analyze this file for N+1 issues", etc.

- **Filament v4 Code Review Skill** (`fillamentv4-code-review`)
  - Location: `prompter/skills/fillamentv4-code-review/SKILL.md`
  - Use for: Reviewing Filament admin resources, widgets, and pages for UI/UX, modal usage, i18n, and permission checks.
  - Trigger by asking: "Review all Filament resources for best practices", "Check admin panel for i18n issues", etc.

**How to Use Skills:**
- Simply describe your review or analysis goal in your request (e.g., "review code for N+1 queries", "analyze Filament resource for i18n").
- The AI agent will load the relevant SKILL.md and follow its workflows and review criteria.
- You can specify a file, directory, or focus area (e.g., security, service layer) in your request.

See each SKILL.md for detailed workflows, review steps, and bundled scripts or references.
<!-- PROMPTER:START -->
# Prompter Instructions

These instructions are for AI assistants working in this project.

Always open `@/prompter/AGENTS.md` when the request:
- Mentions planning or proposals (words like proposal, spec, change, plan)
- Introduces new capabilities, breaking changes, architecture shifts, or big performance/security work
- Sounds ambiguous and you need the authoritative spec before coding

Use `@/prompter/AGENTS.md` to learn:
- How to create and apply change proposals
- Spec format and conventions
- Project structure and guidelines
- Show Remaining Tasks

<!-- PROMPTER:END -->

# AGENTS — Project Knowledge Base

## 1. 📍 Project Summary

**Business Purpose:**
The Event Ticket Management System is an enterprise-grade Laravel-based SaaS platform that orchestrates the complete event lifecycle—from creation through ticket distribution, payment processing, and real-time QR code-based venue access control. The system addresses critical operational challenges in event management through automation and real-time data synchronization.

**Product Type Classification:**
SaaS Event Management & Digital Ticketing Platform

**Development Tools & Standards:**
- **Laravel IDE Helper**: Used by default for generating model doc blocks and type hints
  - Run `php artisan ide-helper:generate` to generate IDE helper files
  - Run `php artisan ide-helper:models` to generate model doc blocks
  - Run `php artisan ide-helper:meta` to generate meta files for PhpStorm
  - Automatically updates when models change to maintain accurate autocomplete
  - Provides full type safety and IDE support for Eloquent models

**Current Implementation Status:**
🟢 **Completed (Core Ecosystem):**
- ✅ Authentication system (Laravel Breeze + Livewire)
- ✅ Google OAuth integration (Laravel Socialite)
- ✅ Comprehensive authorization (Spatie Permission + Shield)
- ✅ Role-based access control (5 predefined roles)
- ✅ Granular permission system (19 permissions across 5 categories)
- ✅ Admin interfaces for user/role/permission management (Filament)
- ✅ Activity logging (Spatie Activity Log)
- ✅ Multi-language support (English, Indonesian)
- ✅ Event Management Module (Core CRUD, Ticket Types, Banners)
- ✅ **EPIC-006: Payment Verification Workflow**
  - ✅ Order & OrderItem management
  - ✅ Visitor-facing Checkout & Payment Proof upload (Livewire)
  - ✅ Finance Admin verification queue with lightbox review (Filament)
  - ✅ Inventory reservation vs deduction logic
- ✅ **EPIC-007: Digital Ticket Issuance & QR Generation**
  - ✅ Modern/Premium PDF generation (Invoice & Tickets) using DomPDF
  - ✅ Secure QR Code generation and scannability optimization
  - ✅ Async processing workflow (Queue Jobs) for post-payment actions
  - ✅ Secure download routes for authenticated users

🟡 **Planned (Next Priorities):**
- ⏳ Check-in Management System (Scanner Interface)
- ⏳ Visitor Dashboard & Digital Wallet
- ⏳ Finance Dashboard & Reporting (Advanced Charts)
- ⏳ Analytics & Reporting Module
- ⏳ Seating Management System (Redis-backed interactive map)
- ⏳ Search & Discovery Features

**Permission Structure (Implemented):**

**Event Permissions:**
- `events.view`, `events.create`, `events.edit`, `events.delete`, `events.publish`

**Ticket Permissions:**
- `tickets.view`, `tickets.create`, `tickets.edit`, `tickets.delete`, `tickets.check-in`

**User Permissions:**
- `users.view`, `users.edit`, `users.delete`
- `users.assign-roles`, `users.manage-permissions`

**Financial Permissions:**
- `finance.view-reports`, `finance.verify-payments`, `finance.process-refunds`

**System Permissions:**
- `system.view-logs`, `system.manage-settings`

**Target Users:**
- **Super Admin**: Full system access, user/role/permission management
- **Event Manager**: Event and ticket management (when implemented)
- **Finance Admin**: Payment verification and financial reporting (when implemented)
- **Check-in Staff**: QR scanning and validation (when implemented)
- **Visitor**: Event browsing, ticket purchase, profile management

---

## 2. 🧱 Tech Stack

**Frontend:**
- **Framework**: Filament v4.7 (Admin), Laravel Livewire v3.x (Visitor)
- **Core Dependencies**:
  - `filament/filament`: ^4.7
  - `bezhansalleh/filament-shield`: ^4.1
- **State Management**: Livewire reactive components, Alpine.js for micro-interactions
- **Styling**: Tailwind CSS 3.x with custom design system
- **UI Components**: Blade UI Kit, Livewire UI, custom component library
- **Rich Text**: TinyMCE or CKEditor for event descriptions
- **PDF & Assets**:
  - `barryvdh/laravel-dompdf`: ^3.0 (Invoice/Ticket generation)
  - `simplesoftwareio/simple-qrcode`: ^4.2 (QR Code generation)

**Backend:**
- **Language**: PHP 8.2+
- **Framework**: Laravel 11.31
- **Runtime**: PHP-FPM with OPcache enabled
- **Authentication**: Laravel Sanctum (API), Spatie Laravel Permission (RBAC), Laravel Socialite
- **Internationalization**: Laravel Localization (i18n) with session-based language switching
- **Queue**: Laravel Queue with Database driver
- **Task Scheduling**: Laravel Scheduler (cron)

**Database:**
- **Primary**: MySQL 8.0+ or PostgreSQL 13+
- **ORM**: Eloquent with eager loading optimization
- **Migrations**: Laravel migrations with version control
- **Seeders**: Factory-based test data generation

**Cache/Queue:**
- **Cache**: Database driver for session, application cache
- **Queue**: Database-backed Laravel queues for async processing
- **Pub/Sub**: Laravel Broadcasting (optional, if needed)

**Infrastructure:**
- **Hosting**: VPS/Cloud (AWS, DigitalOcean, Linode) or Laravel Forge-managed
- **Web Server**: Nginx or Apache with reverse proxy
- **CI/CD**: GitHub Actions or GitLab CI recommended
- **Containers**: Docker support optional (not primary deployment)
- **Storage**: Local filesystem or S3-compatible for file uploads

**AI/ML:**
- Not applicable in MVP 1.0

---

## 3. 🏗️ Architecture Overview

**System Architecture (Monolithic MVC with Service Layer):**

```
┌───────────────────────────────────────────────────────────────────────────┐
│                            CLIENT LAYER                                   │
├──────────────────────┬────────────────────────────────────────────────────┤
│  ADMIN INTERFACE     │           VISITOR INTERFACE                        │
│  (Filament v4.7)    │           (Livewire Components)                    │
│  - Livewire-based    │           - Reactive SPA-like                      │
│  - SEO-friendly      │           - Real-time updates                      │
│  - Form-heavy CRUD   │           - Progressive enhancement                │
└──────────────────────┴────────────────────────────────────────────────────┘
                                    ↓
┌───────────────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                                │
├───────────────────────────────────────────────────────────────────────────┤
│  Controllers (Admin)  │  Livewire Components (Visitor)  │  API Routes    │
│  - EventController    │  - EventList                     │  - QR Validation│
│  - OrderController    │  - TicketPurchase               │  - Webhooks     │
│  - UserController     │  - DigitalWallet                │                 │
└───────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌───────────────────────────────────────────────────────────────────────────┐
│                          SERVICE LAYER                                    │
├───────────────────────────────────────────────────────────────────────────┤
│  EventService         │  PaymentService       │  QrCodeService            │
│  - Event CRUD logic   │  - Verification       │  - Generation             │
│  - Publication flow   │  - Inventory control  │  - Validation             │
│  - Duplication        │  - Refund processing  │  - Encryption             │
│                       │                       │                           │
│  NotificationService  │  AnalyticsService     │  CheckInService           │
│  - Email queuing      │  - Real-time metrics  │  - Scanner interface      │
│  - Template rendering │  - Report generation  │  - Manual override        │
└───────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌───────────────────────────────────────────────────────────────────────────┐
│                         REPOSITORY LAYER                                  │
├───────────────────────────────────────────────────────────────────────────┤
│  EventRepository      │  OrderRepository      │  UserRepository           │
│  - Query optimization │  - Transaction mgmt   │  - Role assignment        │
│  - Caching strategy   │  - Inventory locking  │  - Permission caching     │
└───────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌───────────────────────────────────────────────────────────────────────────┐
│                          DATA LAYER                                       │
├───────────────────────────────────────────────────────────────────────────┤
│  Eloquent Models      │  Database (MySQL/PG)  │  Redis Cache              │
│  - Event, Order       │  - Atomic operations  │  - Session storage        │
│  - TicketType         │  - Row-level locking  │  - Real-time data         │
│  - User, Permission   │  - Transactions       │  - Queue jobs             │
└───────────────────────────────────────────────────────────────────────────┘
```

**Service Boundaries:**
- **Event Domain**: Event creation, ticket configuration, publishing workflow
- **Order Domain**: Purchase flow, payment verification, refund processing
- **Access Control Domain**: User management, roles, permissions, authentication
- **Check-in Domain**: QR validation, manual check-in, attendance tracking
- **Analytics Domain**: Metrics aggregation, report generation, export

**Data Flow Patterns:**
1. **Event Publication**: Draft → Validation → Published (with email notifications queued)
2. **Payment Verification**: Upload Proof → Admin Review → Approve/Reject → Inventory Update → Ticket Delivery
3. **QR Validation**: Scan → Decrypt → Verify → Mark Checked-In → Update Dashboard (WebSocket)
4. **Real-time Updates**: Database Event → Redis Pub/Sub → Livewire Component → Browser Update

**Async Processing:**
- **Email Notifications**: Queued via Laravel Queue (Redis driver)
- **Report Generation**: Background jobs for large data exports
- **Image Optimization**: Async processing of uploaded banners
- **Metrics Aggregation**: Scheduled tasks for analytics calculation

---

## 4. 📁 Folder Structure & Key Files (Laravel 11)

```
project-root/
├── app/
│   ├── Console/
│   │   └── Kernel.php
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Legacy Admin (being replaced by Filament)
│   │   │   │   ├── ActivityController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PermissionController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   └── UserController.php
│   │   ├── Filament/              # Filament Admin Panel
│   │   │   ├── Resources/
│   │   │   │   ├── ActivityResource.php
│   │   │   │   ├── PermissionResource.php
│   │   │   │   ├── RoleResource.php
│   │   │   │   └── UserResource.php
│   │   │   ├── Pages/
│   │   │   │   └── PermissionMatrix.php
│   │   │   ├── Widgets/
│   │   │   │   ├── StatsOverview.php
│   │   │   │   ├── UsersChart.php
│   │   │   │   └── LatestActivities.php
│   │   │   ├── Auth/               # Authentication controllers
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── PasswordResetLinkController.php
│   │   │   ├── Controller.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   ├── CheckPermission.php
│   │   │   ├── RedirectBasedOnRole.php
│   │   │   └── SecurityHeaders.php
│   │   └── Requests/
│   ├── Livewire/
│   │   ├── Actions/
│   │   │   └── Logout.php
│   │   ├── Forms/
│   │   │   └── LoginForm.php
│   │   ├── Profile/
│   │   │   └── UpdateProfileInformationForm.php
│   │   └── Welcome.php
│   ├── Jobs/
│   │   └── ProcessOrderCompletion.php    # Post-payment async workflow
│   ├── Mail/
│   │   ├── OrderCreated.php
│   │   ├── PaymentVerificationApproved.php
│   │   └── PaymentVerificationRejected.php
│   ├── Models/
│   │   ├── Event.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── PaymentProof.php
│   │   ├── Ticket.php
│   │   ├── TicketType.php
│   │   └── User.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Services/
│   │   ├── ActivityLogger.php
│   │   ├── InvoiceService.php
│   │   ├── OrderService.php
│   │   ├── PermissionHelper.php
│   │   ├── SecurityMonitor.php
│   │   ├── TicketService.php
│   │   └── UserService.php
│   └── View/
│       └── Components/
│
├── bootstrap/
│   ├── app.php (Application Configuration, Middleware, Exceptions)
│   ├── cache/
│   └── providers.php (Service Providers)
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── permission.php (Spatie Permission)
│   ├── services.php (OAuth, etc.)
│   └── session.php
│
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_02_06_200031_create_permission_tables.php
│   │   ├── 2026_02_07_021548_create_personal_access_tokens_table.php
│   │   └── 2026_02_07_023339_create_activity_log_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── PermissionSeeder.php
│       └── RolePermissionSeeder.php
│
├── lang/                          # Language/Translation files
│   ├── en/                        # English translations
│   │   ├── auth.php
│   │   ├── pagination.php
│   │   ├── passwords.php
│   │   ├── permission.php
│   │   ├── role.php
│   │   ├── user.php
│   │   ├── validation.php
│   │   └── welcome.php
│   └── id/                        # Indonesian translations
│       ├── permission.php
│       ├── role.php
│       ├── user.php
│       └── welcome.php
│
├── docs/
│   ├── AGENTS.md (This file)
│   ├── admin-interface-guide.md
│   ├── deployment-checklist.md
│   ├── permissions-and-roles.md
│   ├── requirement.md (Full requirements specification)
│   └── security-features.md
│
├── prompter/
│   └── changes/
│       ├── active/ (Active change proposals)
│       └── archive/ (Completed changes)
│           ├── 2026-02-06-setup-platform-foundation/
│           ├── 2026-02-07-add-laravel-breeze-auth/
│           └── 2026-02-07-implement-epic-002-remaining-auth/
│
├── public/
│   ├── index.php
│   └── storage/ (symlink)
│
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/
│       │   ├── activity/
│       │   ├── dashboard.blade.php
│       │   ├── permissions/
│       │   ├── roles/
│       │   └── users/
│       ├── components/
│       ├── dashboard.blade.php
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   └── navigation.blade.php
│       ├── livewire/
│       │   └── pages/
│       │       └── auth/
│       │           ├── login.blade.php
│       │           ├── register.blade.php
│       │           ├── forgot-password.blade.php
│       │           └── verify-email.blade.php
│       ├── profile/
│       │   └── partials/
│       ├── profile.blade.php
│       └── welcome.blade.php
│
├── routes/
│   ├── auth.php (Laravel Breeze auth routes)
│   ├── console.php
│   └── web.php
│
├── storage/
│   ├── app/
│   │   ├── private/
│   │   └── public/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   ├── PermissionSystemTest.php
│   │   └── ProfileTest.php
│   └── Unit/
│
├── .env
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpstan.neon
├── phpunit.xml
├── tailwind.config.js
└── vite.config.js
```

### Interface Separation Strategy

| Aspect | Admin Interface | Visitor Interface |
|--------|-----------------|-------------------|
| **Technology** | Filament v4.7 (Livewire-based) | Laravel Livewire v3.x |
| **Resources** | `/app/Filament/Resources` | `/app/Livewire` (minimal currently) |
| **Widgets** | `/app/Filament/Widgets` | N/A |
| **Pages** | `/app/Filament/Pages` | `/resources/views/livewire` |
| **Purpose** | User/role/permission management, activity logs, analytics | Authentication, profile (event features planned) |
| **Auth** | `web` guard (via Filament Shield) | `web` guard (Laravel Breeze) |
| **Route** | `/admin` | Various public routes |

**Critical Configuration Files:**
- **`.env`**: Database credentials, mail server, app keys, OAuth credentials, locale settings
- **`config/app.php`**: Application configuration including locale settings and available languages
- **`config/permission.php`**: Spatie permission cache configuration
- **`config/auth.php`**: Authentication guards and providers
- **`config/services.php`**: Google OAuth configuration
- **`bootstrap/app.php`**: Middleware and exception handling configuration (includes SetLocale middleware)

**Bootstrap/Entry Points:**
- **`public/index.php`**: HTTP request entry point
- **`artisan`**: CLI commands entry point
- **`bootstrap/app.php`**: Application initialization

---

## 5. 🔑 Core Business Logic & Domain Rules

### Event Publication Workflow

**State Transitions:**
```
Draft → (Validation Check) → Published → Active → Completed → Archived
                             ↓ (validation fails)
                           [Stay in Draft]
```

**Validation Rules:**
- Event must have title (5-200 chars), description (50-10,000 chars), date, location
- Event must have at least ONE ticket type configured
- Event must have banner image uploaded (polymorphic File relation)
- Event must have sales window configured (sales_start_at, sales_end_at)

**Side Effects on Publication:**
- Set `published_at` timestamp
- Queue email notifications to subscribed users (if applicable)
- Log audit trail entry: "Event published by [User]"
- Update search index (if search module implemented)

### Payment Verification Flow

**Order Status State Machine:**
```
Created → PendingVerification → (Admin Review) → Paid → Completed
                                      ↓ (rejected)
                                   Rejected → (Refund or Cancel)
```

**Verification Business Rules:**
- BR-PAY-001: Payment proof must be uploaded (JPG, PNG, PDF ≤ 5MB)
- BR-PAY-002: Finance Admin reviews proof against order total
- BR-PAY-003: On APPROVAL:
  - Deduct ticket quantities from `TicketType.quantity`
  - Generate encrypted QR codes for each ticket
  - Create `Ticket` records with status "Valid"
  - Queue email with ticket PDF attachments
  - Log audit: "Payment approved by [Admin] for Order #[ID]"
- BR-PAY-004: On REJECTION:
  - Release reserved inventory (if applicable)
  - Queue rejection email with reason
  - Log audit: "Payment rejected by [Admin] for Order #[ID]: [Reason]"

**Inventory Control:**
- Use database row-level locking during ticket purchase to prevent overselling
- Atomic operations: `TicketType::where('id', $id)->lockForUpdate()->decrement('quantity', $qty)`
- If inventory insufficient, reject order creation with error

### QR Code Validation Rules

**Validation Sequence:**
1. **Signature Verification**: HMAC-SHA256 signature check (tamper detection)
2. **Decryption**: AES-256-CBC decryption of payload
3. **Database Lookup**: Verify `Ticket` exists with matching ID
4. **Status Check**: Ticket status must be "Valid" (not "Used", "Refunded", "Cancelled")
5. **Event Match**: Ticket's event ID matches scanning context
6. **Time Window**: Current time within event's check-in window (optional)
7. **Duplicate Detection**: Check `checked_in_at` is NULL (not already scanned)

**Validation Outcomes:**
- **Success**: Mark ticket as checked-in (`checked_in_at` = now), return success response
- **Already Used**: Return error "Ticket already checked in at [timestamp]"
- **Invalid Ticket**: Return error "Ticket not found or invalid"
- **Wrong Event**: Return error "Ticket not valid for this event"
- **Tampered QR**: Return error "Security validation failed"

### Notification Rules

**Trigger Events:**
- Event published → Email to event followers/subscribers (if feature exists)
- Payment approved → Email with ticket PDF + QR codes
- Payment rejected → Email with rejection reason
- Refund processed → Email with refund confirmation
- Order reminder → 24h before event start (if not checked-in)

**Email Template Requirements:**
- Must include unsubscribe link (legal requirement)
- Use queue system (do NOT send synchronously)
- Log all email attempts based on jobs

### Engagement Workflows

**Seat Selection (10-Minute Lock):**
1. User selects seat -> WebSocket checks availability
2. If available, create temporary Redis key (`seat:123:lock`) with 10m TTL
3. Broadcast "Held" status to other users
4. If purchase complete -> Convert to Permanent Lock (DB status 'sold')
5. If timeout -> Key expires -> Broadcast "Available"

**Social & Testimonials:**
- **Event Love**: Toggle heart -> Update user_favorites table -> Update total_loves count (cache)
- **Testimonial**: User submits -> Admin moderates (optional) -> Published
- **Sharing**: Generate deep link with UTM -> Open native share sheet/modal

**Notifications:**
- **Trigger**: System event (e.g., ticket_issued)
- **Channel Routing**:
  - Urgent (Ticket/Payment) -> SMS + Email + database
  - Marketing -> Email + database
- **Storage**: `notifications` table for in-app history

---

## 6. 🗂️ Data Models / Entities

### Currently Implemented Models

**User** (Fully Implemented)
- **Attributes**: `id`, `name`, `email`, `email_verified_at`, `password`, `avatar`, `google_id`, `remember_token`, `created_at`, `updated_at`
- **Relationships**: 
  - `belongsToMany Role` (via Spatie Permission)
  - `belongsToMany Permission` (via Spatie Permission)
  - `hasMany Activity` (via Spatie Activity Log)
- **Traits**: `HasApiTokens`, `HasFactory`, `HasRoles`, `LogsActivity`, `Notifiable`
- **Implements**: `MustVerifyEmail`

**Role** (Spatie Package - Configured)
- **Predefined Roles**: Super Admin, Event Manager, Finance Admin, Check-in Staff, Visitor
- **Attributes**: `id`, `name`, `guard_name`, `created_at`, `updated_at`
- **Relationships**: `belongsToMany Permission`, `belongsToMany User`

**Permission** (Spatie Package - Configured)
- **Categories**: Events (5), Tickets (5), Users (5), Finance (3), System (2)
- **Total**: 19 permissions
- **Attributes**: `id`, `name`, `guard_name`, `created_at`, `updated_at`
- **Relationships**: `belongsToMany Role`

**Activity** (Spatie Activity Log)
- **Attributes**: `id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `event`, `batch_uuid`, `created_at`, `updated_at`
- **Purpose**: Comprehensive audit trail for all system actions

**Event** (Fully Implemented)
- **Attributes**: `id`, `title`, `slug`, `description`, `event_date`, `location`, `venue_name`, `status`, `seating_enabled`
- **Relationships**: `belongsTo User` (creator), `hasMany TicketType`, `morphMany FileBucket` (banners), `belongsToMany SettingComponent` (categories/tags)

**TicketType** (Fully Implemented)
- **Attributes**: `id`, `event_id`, `name`, `price`, `quantity`, `min_purchase`, `max_purchase`, `sales_start_at`, `sales_end_at`
- **Relationships**: `belongsTo Event`

**FileBucket** (Fully Implemented)
- **Attributes**: `id`, `fileable_type`, `fileable_id`, `file_path`, `url`, `mime_type`, `file_size`
- **Purpose**: Polymorphic file storage for event banners and other media

**Setting** (Fully Implemented)
- **Attributes**: `id`, `key`, `name`
- **Relationships**: `hasMany SettingComponent`

**SettingComponent** (Fully Implemented)
- **Attributes**: `id`, `setting_id`, `name`, `type`, `value`
- **Purpose**: Dynamic master data for event categories, tags, and ticket types


**Order** (Implemented)
- **Attributes**: `id`, `order_number`, `user_id`, `event_id`, `status`, `subtotal`, `discount_amount`, `tax_amount`, `total_amount`, `notes`, `expires_at`, `completed_at`, `created_at`, `updated_at`
- **Relationships**: `belongsTo User`, `belongsTo Event`, `hasMany OrderItem`, `hasMany Ticket`, `hasOne PaymentProof`
- **Status Enum**: `pending_payment`, `pending_verification`, `completed`, `cancelled`, `expired`

**OrderItem** (Implemented)
- **Attributes**: `id`, `order_id`, `ticket_type_id`, `quantity`, `unit_price`, `total_price`
- **Relationships**: `belongsTo Order`, `belongsTo TicketType`

**PaymentProof** (Implemented)
- **Attributes**: `id`, `order_id`, `file_bucket_id`, `status` (pending/approved/rejected), `verified_by_id`, `verified_at`, `rejection_reason`
- **Relationships**: `belongsTo Order`, `belongsTo FileBucket`, `belongsTo User` (verifier)

**Ticket** (Implemented)
- **Attributes**: `id`, `order_item_id`, `ticket_type_id`, `ticket_number`, `holder_name`, `status` (active/used/cancelled), `qr_code_content`, `checked_in_at`
- **Relationships**: `belongsTo OrderItem`, `belongsTo TicketType`

**Planned Models (Not Yet Implemented)**
- **CheckInLog**: Detailed tracking of scan attempts and locations.
- **SeatLayout**: Definitions for venue seating grids.
- **SeatReservation**: Junction between Seats and Orders.

---

## 7. 🧠 Domain Vocabulary / Glossary

**Authentication & Authorization:**
- **Guard**: Laravel authentication mechanism (currently using `web` guard for all users)
- **Role**: Named group of permissions (Super Admin, Event Manager, Finance Admin, Check-in Staff, Visitor)
- **Permission**: Granular access control (e.g., `users.view`, `events.create`)
- **Activity Log**: Audit trail of user actions tracked by Spatie Activity Log
- **OAuth**: Open Authorization protocol (Google OAuth for social login)
- **Sanctum**: Laravel package for API token authentication and SPA authentication

**User Management:**
- **User**: Registered account with authentication credentials
- **Profile**: User information including name, email, avatar
- **Avatar**: User profile picture (stored in `storage/app/public/avatars`)
- **Email Verification**: Process to confirm user email ownership

**Internationalization:**
- **Locale**: Language setting for the application (e.g., `en`, `id`)
- **Translation File**: PHP file containing language-specific strings (e.g., `lang/en/auth.php`)
- **Language Switcher**: UI component allowing users to change application language
- **Fallback Locale**: Default language used when translation is missing
- **Translation Key**: Identifier for a translatable string (e.g., `welcome.title`)

**System Concepts:**
- **Middleware**: HTTP request filtering layer (CheckPermission, RedirectBasedOnRole, SecurityHeaders, SetLocale)
- **Service Layer**: Business logic encapsulation (ActivityLogger, PermissionHelper, SecurityMonitor, UserService)
- **Seeder**: Database population script for initial data (PermissionSeeder, RolePermissionSeeder)
- **Migration**: Database schema version control

**Planned Domain Terms (Not Yet Implemented):**
- **Event**: Scheduled occurrence with tickets for sale
- **Ticket Type**: Category of ticket with specific pricing and inventory
- **Order**: Purchase transaction containing one or more tickets
- **QR Code**: Encrypted ticket validation mechanism
- **Check-in**: Process of validating ticket at venue entry
- **Seat**: Individual location in a venue (for seated events)
- **Venue**: Physical location with seating layout

---

## 8. 👥 Target Users & Personas

### Super Admin
**Description:** System owner with unrestricted access to all modules and data
**Primary Responsibilities:**
- User management (create, delete, assign roles)
- System configuration and settings
- Cross-event analytics and reporting
- Audit trail review

### Event Manager
**Description:** Event organizer responsible for creating and managing their own events
**Primary Responsibilities:**
- Event creation and configuration
- Ticket type setup and pricing
- Sales monitoring
- Attendee communication

### Finance Admin
**Description:** Financial oversight role focused on payment verification and reconciliation
**Primary Responsibilities:**
- Payment proof verification
- Refund processing
- Financial reporting
- Revenue reconciliation

### Check-in Staff
**Description:** Venue personnel responsible for ticket validation at event entry
**Primary Responsibilities:**
- QR code scanning
- Ticket validation
- Manual check-in (when QR fails)
- Live attendance monitoring

### Visitor (Attendee)
**Description:** End-user purchasing and consuming tickets
**Primary Responsibilities:**
- Event discovery and browsing
- Ticket purchase
- Payment proof submission
- Digital wallet management
- Event check-in (presenting QR code)

---

## 9. ✨ UI/UX Principles

### Current Implementation (Admin & Auth Interfaces)

**Admin Interface (Filament v4.7) - Implemented:**
- **Livewire-powered admin panel** with real-time updates
- **Filament Resources** for Users, Roles, Permissions, Settings, and Activity Logs
- **Advanced filtering and search** across all resources
- **Bulk actions** for efficient management (delete, assign roles, etc.)
- **Dashboard widgets** for key metrics (Total Users, Users by Role, Recent Activity)
- **Permission-based navigation** showing only accessible items per role
- **Filament Shield integration** for seamless Spatie Permission compatibility
- **Export functionality** for activity logs (CSV)
- **Custom pages** for Permission Matrix view
- **Toast notifications** for all admin actions
- **Responsive design**: Mobile, tablet, and desktop support
- **Theme customization** matching project branding
- **⚠️ IMPORTANT - Modal/Slideover Pattern**: All create/edit operations MUST use modal dialogs or slideovers (NOT separate pages). This keeps users in context and provides a better UX. Use `->modal()` for simple forms or `->slideOver()` for complex forms in Filament table actions.
- **⚠️ IMPORTANT - Form Field Best Practices**: 
  - **Placeholders Required**: ALL form fields (TextInput, Select, Textarea, etc.) MUST include meaningful placeholders using `->placeholder()`. This improves UX by providing examples and guidance.
  - **Translations Required**: ALL labels and placeholders MUST use Laravel's translation functions (`__()`) for multi-language support.
  - **Example**:
    ```php
    TextInput::make('email')
        ->label(__('user.email'))
        ->placeholder(__('user.email_placeholder'))
    
    TextInput::make('key')
        ->label(__('setting.key'))
        ->placeholder(__('setting.key_placeholder'))
    ```
- **Authentication**: Filament's built-in login/register pages are DISABLED (`->login(false)`). All authentication flows through Laravel Breeze at `/login` and `/register`. Unauthenticated users accessing `/admin` are redirected to Breeze login.

**Admin Interface (Blade) - Deprecated:**
- Custom Blade admin views have been removed
- All functionality migrated to Filament Resources

**Authentication Interface (Livewire) - Implemented:**
- **Mobile-responsive** login and registration forms
- **Clean, modern design** with Tailwind CSS
- **Form validation** with real-time feedback
- **Google OAuth** integration with branded button
- **Password visibility toggle**
- **Remember me** functionality
- **Email verification** workflow (disabled by default)

### Planned UI/UX (Visitor Interface - Not Implemented)

**Visitor Interface (Livewire) - Planned:**
- **Mobile-first design** with touch-optimized controls (≥44px tap targets)
- **Card-based event listings** with lazy loading on scroll
- **Skeleton screens** during data loading (no spinners)
- **Progressive disclosure** for complex forms (ticket purchase multi-step)
- **Full-screen QR display** with brightness boost for scanning
- **Bottom sheet modals** for mobile actions
- **Swipe gestures** for navigation (ticket gallery)
- **Offline indicators** with sync status

### Accessibility Requirements (Partially Implemented)

**Currently Applied:**
- **Semantic HTML**: Proper heading hierarchy in auth and admin views
- **Keyboard navigation**: Tab order and focus indicators
- **Color contrast**: WCAG AA compliance in implemented interfaces
- **Form labels**: All inputs properly labeled

**Planned:**
- **ARIA labels** for complex interactive components
- **Screen reader support** for dynamic content
- **Skip links** for main navigation

---

## 9. 🌐 Internationalization (i18n)

### Supported Languages

The application supports multi-language functionality with the following languages:

| Language Code | Language Name | Status | Priority |
|---------------|---------------|--------|----------|
| `en` | English | ✅ Full Support | **PRIMARY** |
| `id` | Indonesian | ✅ Full Support | Secondary |

### Language Configuration

**Configuration Files:**
- **`config/app.php`**: Contains locale configuration
  - `locale`: Default application locale (default: `en` - **PRIMARY**)
  - `fallback_locale`: Fallback locale when translation is missing (default: `en`)
  - `locales`: Array of available languages with display names

```php
'locales' => [
    'en' => 'English',  // PRIMARY language
    'id' => 'Indonesian',
],
```

**IMPORTANT:** English (`en`) is the PRIMARY and REFERENCE language. All translation keys must exist in `lang/en/*.php` before being added to other languages.

### Language Files Structure

**Language Files Location:** `/lang/{locale}/`

**Currently Available Translation Files:**

**English (`lang/en/`) - PRIMARY/REFERENCE:**
- `auth.php` - Authentication related strings
- `pagination.php` - Pagination strings
- `passwords.php` - Password reset strings
- `permission.php` - Permission labels
- `role.php` - Role labels
- `user.php` - User-related strings
- `validation.php` - Validation error messages
- `welcome.php` - Welcome page strings

**Indonesian (`lang/id/`) - Secondary:**
- `permission.php` - Permission labels (Indonesian)
- `role.php` - Role labels (Indonesian)
- `user.php` - User-related strings (Indonesian)
- `welcome.php` - Welcome page strings (Indonesian)

**IMPORTANT:** English (`lang/en/`) is the PRIMARY and REFERENCE language. All translation keys must exist in English before being added to other languages.

### Language Switching Implementation

**Controller:** [`LanguageController`](app/Http/Controllers/LanguageController.php:1)

The [`LanguageController`](app/Http/Controllers/LanguageController.php:14) handles language switching:

```php
public function switch($locale): RedirectResponse
{
    if (array_key_exists($locale, config('app.locales'))) {
        session()->put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
}
```

**Middleware:** [`SetLocale`](app/Http/Middleware/SetLocale.php:1)

The [`SetLocale`](app/Http/Middleware/SetLocale.php:16) middleware sets the application locale based on session:

```php
public function handle(Request $request, Closure $next): Response
{
    if (session()->has('locale')) {
        app()->setLocale(session()->get('locale'));
    }
    return $next($request);
}
```

**Route:** [`routes/web.php`](routes/web.php:11)

Language switch route:
```php
Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');
```

### UI Components

**Language Switcher:** [`resources/views/livewire/welcome/navigation.blade.php`](resources/views/livewire/welcome/navigation.blade.php:1)

The navigation includes a language switcher with EN/ID buttons:
```php
<a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold' : '' }}">
    EN
</a>
<span class="text-gray-400">|</span>
<a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'font-bold' : '' }}">
    ID
</a>
```

### Using Translations in Code

**Blade Templates:**
```php
{{ __('welcome.title') }}
{{ __('auth.failed') }}
{{ __('validation.required') }}
```

**PHP Code:**
```php
__('welcome.title');
trans('welcome.title');
__('messages.welcome', ['name' => $user->name]);
```

**Livewire Components:**
```php
public $message = __('welcome.title');
```

**IMPORTANT:** When adding new translation keys, always create them in `lang/en/*.php` (English) FIRST, then translate to other languages.

### Adding New Languages

To add a new language to the application:

1. **Add to Configuration:** Update [`config/app.php`](config/app.php:85)
   ```php
   'locales' => [
       'en' => 'English',
       'id' => 'Indonesian',
       'fr' => 'French',  // New language
   ],
   ```

2. **Create Language Directory:** Create `/lang/fr/` directory

3. **Create Translation Files:** Copy and translate files from `/lang/en/` (English is the BASE/REFERENCE language) to `/lang/fr/`
   - `auth.php`
   - `validation.php`
   - etc.

4. **Update UI:** Add language button to navigation component

5. **Test:** Verify translations work correctly

**IMPORTANT:** English (`en`) is the PRIMARY and REFERENCE language. All translation keys must exist in `lang/en/*.php` before being added to other languages.

### Best Practices

**DO:**
- Use Laravel's translation functions (`__()`, `trans()`) for all user-facing text
- **ALWAYS create English translations FIRST** in `lang/en/*.php` before adding to other languages
- Keep translation keys consistent across languages
- Use parameterized translations for dynamic content: `__('welcome.hello', ['name' => $name])`
- Test all language switches before deployment
- Keep translation files organized and well-commented

**DON'T:**
- Hardcode user-facing text in views or controllers
- Mix languages in the same translation file
- Use machine translation without human review
- Forget to update all language files when adding new keys
- Add translation keys to other languages without first adding to English (`lang/en/*.php`)

### Environment Variables

**`.env` Configuration:**
```env
APP_LOCALE=en              # Default application locale
APP_FALLBACK_LOCALE=en     # Fallback locale
```

---

## 10. 🔒 Security & Privacy Rules

### Authentication Model
- **Multi-guard authentication**: 
  - `web` guard for visitors (session-based)
  - `admin` guard for staff (session-based with stricter timeout, optional)
- **Password requirements**: Min 8 characters, 1 uppercase, 1 lowercase, 1 number
- **CSRF Protection**: Laravel CSRF tokens on all forms
- **Session timeout**: 2 hours inactivity for admin, 24 hours for visitors
- **Login throttling**: 5 attempts per minute, 10-minute lockout

### Authorization/RBAC Implementation
- **Spatie Laravel Permission** package
- **Permission caching**: Redis-backed with 24-hour TTL
- **Policy-based authorization**: Eloquent policies for Event, Order, Ticket
- **Middleware enforcement**: `role:admin`, `permission:events.create`

### Sensitive Data Handling
- **PII Encryption**: 
  - User passwords: bcrypt hashed
  - QR codes: AES-256-CBC encrypted payload
- **Data Masking**: 
  - Order list shows masked customer emails (j***@example.com)
- **XSS Prevention**: Blade automatic escaping (`{{ $var }}`), use `{!! !!}` only for trusted HTML

---

## 10. 🏛️ Service Layer Pattern

### Purpose & Philosophy
The Service Layer encapsulates all business logic, keeping controllers thin and promoting code reusability across different interfaces (Web, API, Console, Livewire).

### Service Layer Architecture

```
Controller/Component → Service → Repository/Model → Database
```

### Service Class Structure

**Naming Convention:**
- PascalCase with "Service" suffix
- Singular noun representing the domain (e.g., `EventService`, not `EventsService`)
- Located in `app/Services/` directory

**Core Principles:**
1. **Single Responsibility**: Each service handles one domain concept
2. **Stateless**: Services should not maintain state between method calls
3. **Dependency Injection**: Inject repositories, other services, or Laravel facades
4. **Validation**: Handle input validation and business rule enforcement
5. **Transaction Management**: Wrap database operations in transactions when needed

### Service Method Patterns

**CRUD Operations:**
```php
class EventService
{
    public function createEvent(array $data): Event
    {
        // Validation, business rules, slug generation
        return Event::create($validatedData);
    }

    public function updateEvent(Event $event, array $data): Event
    {
        // Permission checks, business rule validation
        $event->update($validatedData);
        return $event;
    }

    public function deleteEvent(Event $event): bool
    {
        // Business rule checks (e.g., can't delete if tickets sold)
        return $event->delete();
    }
}
```

**Business Operations:**
```php
class EventService
{
    public function publishEvent(Event $event): Event
    {
        // Business validation: must have ticket types, banner, etc.
        $this->validateEventCanBePublished($event);
        
        DB::transaction(function () use ($event) {
            $event->update(['status' => 'published', 'published_at' => now()]);
            $this->queueNotificationEmails($event);
        });
        
        return $event->refresh();
    }
}
```

### Service Usage Examples

**In Controllers:**
```php
class EventController extends Controller
{
    public function __construct(private EventService $eventService) {}
    
    public function store(StoreEventRequest $request)
    {
        $event = $this->eventService->createEvent($request->validated());
        return redirect()->route('events.show', $event);
    }
}
```

**In Livewire Components:**
```php
class EventPurchase extends Component
{
    public function purchase()
    {
        try {
            $order = $this->orderService->createOrder($this->validatedData);
            $this->dispatch('orderCreated', $order->id);
        } catch (ValidationException $e) {
            $this->setError('purchase', $e->getMessage());
        }
    }
}
```

### Available Services

**Core Services:**
- `EventService`: Event CRUD, publishing workflow, validation
- `OrderService`: Order creation, payment verification, refund processing
- `TicketService`: Ticket generation, QR code creation, validation
- `UserService`: User management, role assignment, profile updates
- `PaymentService`: Payment processing, verification workflows
- `NotificationService`: Email queuing, template rendering, delivery

**Specialized Services:**
- `QrCodeService`: QR generation, encryption, validation logic
- `AnalyticsService`: Metrics calculation, report generation
- `SearchService`: Event search, filtering, indexing
- `SeatService`: Seat selection, locking, mapping

### Service Testing Strategy

**Unit Testing:**
- Test each service method in isolation
- Mock repositories and external dependencies
- Test business rule validation and edge cases

**Example Test:**
```php
class EventServiceTest extends TestCase
{
    public function test_publishes_event_with_valid_data()
    {
        $event = Event::factory()->create(['status' => 'draft']);
        
        $publishedEvent = $this->eventService->publishEvent($event);
        
        $this->assertEquals('published', $publishedEvent->status);
        $this->assertNotNull($publishedEvent->published_at);
    }
}
```

### Best Practices

**DO:**
- Keep services focused on business logic
- Use dependency injection for repositories and other services
- Handle validation and business rule enforcement
- Use database transactions for multi-step operations
- Return domain objects (Models), not arrays

**DON'T:**
- Put HTTP-specific logic in services
- Access request/response objects directly
- Mix presentation logic with business logic
- Create "god" services that handle everything
- Use static methods (except for factory methods)

### Integration with Other Patterns

**Repository Pattern (Optional):**
```php
class EventService
{
    public function __construct(
        private EventRepository $eventRepository,
        private NotificationService $notificationService
    ) {}
}
```

**Policy Integration:**
```php
class EventService
{
    public function deleteEvent(Event $event): bool
    {
        $this->authorize('delete', $event); // Uses Policy
        return $event->delete();
    }
}
```

---

## 11. 🤖 Coding Conventions & Standards

### Development Rules for AI Agents

**CRITICAL RULES - MUST FOLLOW:**

1.  **Architecture & Design Pattern**
    -   **Fat Model, Skinny Controller**: Move complex business logic into specialized Service Classes in `app/Services`.
    -   **Service Layer Integration**: Controllers should only handle request parsing and response formatting. All logic goes to Services.
    -   **Strict Typing**: ALWAYS use Type Hinting and Return Types for every method to satisfy Larastan/PHPStan (Level 5 or higher).

2.  **Code Style & Formatting (PSR-12)**
    -   **Naming**:
        -   Methods: `camelCase`
        -   Variables/Properties/DB Columns: `snake_case`
        -   Classes: `PascalCase`
    -   **Imports & Namespaces**:
        -   **NO Inline Namespaces**: Never use Fully Qualified Class Names (FQCN) in code (e.g., avoid `\App\Models\Order`).
        -   **Explicit Imports**: Always `use` classes at the top of the file.
        -   **Sorted Imports**: Alphabetically sort imports and remove unused ones.

3.  **Documentation & IDE Support**
    -   **Docblocks**: Add `@param` and `@return` tags for all methods, especially for complex arrays or Filament types.
    -   **IDE Helper**: Ensure Models have PHPDoc blocks compatible with `laravel-ide-helper` (`@property`, `@method`).

4.  **Filament & UI Standards**
    -   **Modals over Pages**: Use Modals/Slideovers for Create/Edit actions in Resources.
    -   **Internationalization**: All labels/placeholders MUST use `__()`. English (`en`) is PRIMARY language - always create/update `lang/en/*.php` files FIRST before translating to other languages.

5.  **Never Invent Endpoints, Fields, or Models**
    -   Only use existing database tables, columns, and relationships (consult migrations).
    -   Check `routes/web.php` before using endpoints.

6.  **Match Existing Coding Style**
    -   Use existing service layer patterns (`ActivityLogger`, `PermissionHelper`).
    -   Follow Filament Resource patterns.

7.  **Modify Only Necessary Parts**
    -   Make surgical, targeted changes. return diffs/patch format.

8.  **Ask Before Executing Risky Changes**
    -   Migrations, auth logic changes, config changes require approval.

### Function/Method Naming Examples
-   **camelCase for all methods**:
    -   ✅ `createEvent()`, `verifyPayment()`
-   **Verb-first naming**:
    -   ✅ `getActiveEvents()`
-   **Boolean methods with is/has/can prefix**:
    -   ✅ `isPublished()`, `hasDiscount()`

---

## 11. 🔄 Integration Map

### External Service Integrations

**Google OAuth (Authentication):**
- **Purpose**: Social login for visitor registration
- **Integration Point**: Laravel Socialite package
- **Configuration**: `config/services.php` with OAuth client ID and secret

**Email Provider (SMTP/API):**
- **Purpose**: Transactional emails (ticket delivery, payment confirmations)
- **Configuration**: `.env` variables (`MAIL_DRIVER`, `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`)
- **Queue Integration**: All emails sent via Laravel Queue

**File Storage (S3-Compatible):**
- **Purpose**: Banner images, payment proofs, generated QR codes
- **Configuration**: `config/filesystems.php` with disk configurations

### Internal Service Communication

**Livewire ↔ Backend:**
- **Protocol**: HTTP POST requests with CSRF token
- **Data Format**: JSON payload
- **Example**: `TicketPurchase` component calls `OrderController@store` via Livewire action

**Admin Dashboard ↔ Services:**
- **Pattern**: Controller → Service → Repository → Model
- **Example**: `EventController@store` → `EventService@createEvent()` → `Event::create()`

---

## 12. ⚠️ Known Issues & Limitations

### Architectural Constraints

**Single Database:**
- **Limitation**: All modules share one database, no separation of concerns at data layer
- **Method**: Use service boundaries, avoid direct model access across modules

**Session-Based Authentication:**
- **Limitation**: Not suitable for mobile native apps (if built in future)
- **Workaround**: Use Laravel Sanctum for API tokens when needed

### Performance Considerations

**Real-Time Dashboard:**
- **Concern**: WebSocket connections may not scale beyond 1,000 concurrent users
- **Mitigation**: Implement connection pooling, consider SSE as alternative

**QR Code Generation:**
- **Concern**: Synchronous generation during payment approval slows response time
- **Mitigation**: Move to async job processing (queued)

---

## 14. 🗺️ Roadmap & Future Plans

### Completed (MVP 0.5 - Authentication & Authorization)
- ✅ Laravel 11 project setup with Livewire and Tailwind CSS
- ✅ Authentication system (Laravel Breeze)
- ✅ Google OAuth integration
- ✅ Role-based access control (5 roles)
- ✅ Granular permission system (19 permissions)
- ✅ Filament v4.7 admin panel integration with Shield
- ✅ Filament Resources for Users, Roles, Permissions, and Activity Logs
- ✅ Admin dashboard widgets (stats, charts, recent activity)
- ✅ Activity logging and security monitoring
- ✅ User profile management with avatar upload
- ✅ Permission Matrix custom page
- ✅ Multi-language support (English, Indonesian) with session-based language switching

### Next Phase (MVP 1.0 - Event Management Core)
**Priority: High | Timeline: TBD**
- ⏳ Event CRUD operations
- ⏳ Ticket type configuration
- ⏳ Event publishing workflow
- ⏳ Basic event listing (visitor interface)
- ⏳ Event search and filtering

### Future Phases

**MVP 1.5 - Ticketing & Payment**
- ⏳ Ticket purchase flow
- ⏳ Payment proof upload
- ⏳ Payment verification workflow
- ⏳ QR code generation
- ⏳ Digital ticket delivery

**MVP 2.0 - Check-in & Validation**
- ⏳ QR code scanning interface
- ⏳ Ticket validation logic
- ⏳ Check-in dashboard
- ⏳ Manual override capabilities

**MVP 2.5 - Advanced Features**
- ⏳ Seating management system
- ⏳ Interactive seat selection
- ⏳ Notification system
- ⏳ Analytics and reporting
- ⏳ Financial dashboard

**Future Enhancements (Post-MVP)**
- Multi-factor authentication (2FA)
- IP-based access restrictions
- CAPTCHA on login
- Mobile native app
- Advanced analytics with ML insights
- Automated refund processing
- Event recommendation engine

---

## 15. ⚠️ Known Issues & Limitations

### Current Limitations

**Authentication:**
- Single guard (`web`) for all users - no separate admin guard
- Session timeout not yet configured (using Laravel defaults)
- No multi-factor authentication (planned for MVP 2.0)

**Performance:**
- Permission caching uses database driver (Redis recommended for production)
- No query optimization yet implemented
- Activity logs not automatically cleaned up

**Security:**
- Rate limiting configured but not extensively tested
- Security headers middleware basic implementation
- No IP-based access restrictions

**UI/UX:**
- Admin interface not fully mobile-optimized
- No dark mode support yet
- Limited accessibility features

### Architectural Constraints

**Single Database:**
- **Limitation**: All modules share one database, no separation of concerns at data layer
- **Mitigation**: Use service boundaries, avoid direct model access across modules

**Session-Based Authentication:**
- **Limitation**: Not suitable for mobile native apps (if built in future)
- **Workaround**: Laravel Sanctum already installed for future API token support

**Monolithic Architecture:**
- **Limitation**: All features in single codebase
- **Mitigation**: Service layer pattern provides logical separation

---

## 16. 🧪 Testing Strategy

### Current Test Coverage

**Feature Tests (Implemented):**
- ✅ Authentication flow tests (`tests/Feature/Auth/`)
  - Login, registration, password reset, email verification
  - Google OAuth callback handling
- ✅ Permission system tests (`tests/Feature/PermissionSystemTest.php`)
  - Role assignment, permission checking
- ✅ Profile management tests (`tests/Feature/ProfileTest.php`)
  - Profile updates, avatar upload, password changes
- ✅ Admin functionality tests (`tests/Feature/Admin/`)
  - User management, role management

**Unit Tests:**
- ⏳ Service layer tests (planned)
- ⏳ Helper function tests (planned)

### Testing Tools & Configuration

**Configured Tools:**
- **PHPUnit**: Unit and feature testing framework
- **PHPStan**: Static analysis (configured in `phpstan.neon`)
- **Laravel Pint**: Code style enforcement
- **Husky**: Pre-commit hooks for linting

**Test Database:**
- SQLite in-memory database for fast test execution
- Configured in `phpunit.xml`

### Testing Best Practices

**When Writing Tests:**
1. Follow existing test patterns in `/tests/Feature` and `/tests/Unit`
2. Use descriptive test names: `test_<action>_<expected_result>`
3. Use factories for test data generation
4. Clean up test data after each test
5. Test both happy path and error cases
6. Mock external services (OAuth, email, etc.)

### Database Testing Rules

**CRITICAL - Data Preservation:**
- **NEVER use `RefreshDatabase` trait** in tests - this will delete all existing data
- **NEVER delete old data in tables** during test execution
- **Use in-memory SQLite database** for isolated test execution (already configured in `phpunit.xml`)
- **Create test-specific data** using factories, never modify existing records
- **Test isolation**: Each test should create its own data and clean up after itself
- **Seeders**: Only use seeders in development/production, never in test suites

**Rationale:**
- Preserves development data and seeders
- Prevents accidental data loss in development environment
- Ensures tests are deterministic and isolated
- Allows parallel test execution without conflicts

**Example - Correct Test Pattern:**
```php
public function test_create_ticket()
{
    // ✅ CORRECT: Create test data with factory
    $ticketType = TicketType::factory()->create();
    $ticket = Ticket::factory()->for($ticketType)->create();
    
    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'ticket_type_id' => $ticketType->id,
    ]);
    
    // Clean up created test data
    $ticket->delete();
    $ticketType->delete();
}
```

**Example - INCORRECT Pattern:**
```php
// ❌ INCORRECT: Never use RefreshDatabase
use RefreshDatabase; // FORBIDDEN

// ❌ INCORRECT: Never delete existing data
Ticket::truncate(); // FORBIDDEN
Ticket::where('status', 'old')->delete(); // FORBIDDEN
```

**Running Tests:**
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run with coverage
php artisan test --coverage

# Run PHPStan
composer phpstan
```

---

## 17. 🧯 Troubleshooting Guide

### Common Issues & Solutions

**Authentication Issues:**

**Problem**: Google OAuth redirect not working
- **Solution**: Check `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` in `.env`
- **Solution**: Verify callback URL matches Google Console configuration
- **Solution**: Ensure `APP_URL` in `.env` matches your domain

**Problem**: Permission denied errors
- **Solution**: Run `php artisan permission:cache-reset`
- **Solution**: Check user roles: `php artisan tinker` → `User::find(1)->roles`
- **Solution**: Verify permission exists in database

**Problem**: Email verification not sending
- **Solution**: Check mail configuration in `.env`
- **Solution**: Verify queue is running: `php artisan queue:work`
- **Solution**: Check `storage/logs/laravel.log` for errors

**Database Issues:**

**Problem**: Migration errors
- **Solution**: Reset database: `php artisan migrate:fresh --seed`
- **Solution**: Check database connection in `.env`
- **Solution**: Ensure database exists and credentials are correct

**Problem**: Permission tables not found
- **Solution**: Run `php artisan migrate`
- **Solution**: Publish Spatie Permission migrations: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`

**Development Issues:**

**Problem**: Changes not reflecting
- **Solution**: Clear caches: `php artisan optimize:clear`
- **Solution**: Restart Vite: `npm run dev`
- **Solution**: Clear browser cache

**Problem**: PHPStan errors
- **Solution**: Run `composer phpstan` to see full error list
- **Solution**: Check `phpstan.neon` for excluded paths
- **Solution**: Add proper type hints to methods

**Problem**: Livewire component not updating
- **Solution**: Check browser console for JavaScript errors
- **Solution**: Verify Livewire assets are published
- **Solution**: Clear Livewire cache: `php artisan livewire:discover`

**Filament Issues:**

**Problem**: Filament admin panel not accessible
- **Solution**: Verify user has admin role: `php artisan tinker` → `User::find(1)->roles`
- **Solution**: Check Filament permissions: `php artisan shield:generate --all`
- **Solution**: Clear Filament cache: `php artisan filament:cache-clear`
- **Solution**: Verify Filament routes: `php artisan route:list | grep filament`

**Problem**: Filament Shield permissions not working
- **Solution**: Publish Filament Shield config: `php artisan vendor:publish --tag=filament-shield-config`
- **Solution**: Regenerate permissions: `php artisan shield:generate --all`
- **Solution**: Reset permission cache: `php artisan permission:cache-reset`
- **Solution**: Check policy classes exist for resources

**Problem**: Filament resources not showing in navigation
- **Solution**: Check user has `view_any` permission for the resource
- **Solution**: Verify resource is registered in Filament panel provider
- **Solution**: Check navigation permissions in resource class
- **Solution**: Clear config cache: `php artisan config:clear`

### Debugging Tools

**Laravel Debugging:**
```bash
# View logs in real-time
php artisan pail

# Tinker (REPL)
php artisan tinker

# Route list
php artisan route:list

# Clear all caches
php artisan optimize:clear
```

**Database Debugging:**
```bash
# Check database connection
php artisan db:show

# Run specific seeder
php artisan db:seed --class=PermissionSeeder

# Check permissions
php artisan tinker >>> Permission::all()->pluck('name')
```

---

## 18. 📞 Ownership / Responsibility Map

### Module Ownership

**Authentication & Authorization (EPIC-002)**
- **Owner**: Technical Lead
- **Status**: ✅ Completed
- **Components**: 
  - Laravel Breeze integration
  - Google OAuth
  - Spatie Permission system
  - User/Role/Permission admin interfaces
  - Activity logging

**User Management**
- **Owner**: Technical Lead
- **Status**: ✅ Completed
- **Components**:
  - User CRUD operations
  - Profile management
  - Avatar upload
  - Password management

**Event Management (Planned)**
- **Owner**: TBD
- **Status**: ⏳ Not Started
- **Components**:
  - Event CRUD
  - Ticket configuration
  - Publishing workflow

**Payment & Ticketing (Planned)**
- **Owner**: TBD
- **Status**: ⏳ Not Started
- **Components**:
  - Payment verification
  - QR code generation
  - Ticket delivery

### Technical Responsibilities

**Backend Development:**
- Laravel controllers, models, services
- Database migrations and seeders
- API endpoints
- Business logic implementation

**Frontend Development:**
- Blade templates (admin interface)
- Livewire components (visitor interface)
- Tailwind CSS styling
- JavaScript interactions

**DevOps & Infrastructure:**
- Server configuration
- Database management
- Deployment pipelines
- Monitoring and logging

**Quality Assurance:**
- Test writing and maintenance
- Code review
- PHPStan compliance
- Security audits

### Contact & Support

**For Development Questions:**
- Check this AGENTS.md file first
- Review `/docs` directory for specific guides
- Check archived changes in `/prompter/changes/archive`

**For Bug Reports:**
- Check `storage/logs/laravel.log`
- Run `php artisan pail` for real-time logs
- Include steps to reproduce

---

## 13. 📜 Change Log / Implementation History

| Date | Change | Description | Reference |
|------|--------|-------------|-----------|
| 2026-02-06 | Setup Platform Foundation | Initial project setup with Laravel 11, Livewire, and core dependencies. | [Details](../prompter/changes/archive/2026-02-06-setup-platform-foundation) |
| 2026-02-07 | Add Laravel Breeze Auth | Implemented authentication with Breeze, Livewire, and Google OAuth. | [Details](../prompter/changes/archive/2026-02-07-add-laravel-breeze-auth) |
| 2026-02-07 | Implement EPIC-002 Auth | Comprehensive authorization system with granular permissions, admin interfaces, and security enhancements. | [Details](../prompter/changes/archive/2026-02-07-implement-epic-002-remaining-auth) |
| 2026-02-07 | Integrate Filament Admin Panel | Migrated admin interface to Filament v4.7 with Shield integration, dashboard widgets, and advanced resource management. | [Details](../prompter/changes/archive/2026-02-07-integrate-filament-admin-panel) |
| 2026-02-09 | Implement Event Management | Implemented Event CRUD, Ticket Types with dynamic settings, and FileBucket for polymorphic media storage. | N/A |

---

**End of Document**

*This AGENTS.md file is the authoritative knowledge base for all AI agents working on the Event Ticket Management System project. It must be consulted before any code generation, documentation modification, or architectural decision.*

**Last Updated:** February 11, 2026
**Version:** 2.2 (English Language Priority Emphasized)
**Maintained By:** AI Agent (Antigravity) + Technical Lead
**Next Review:** March 7, 2026

**Document Status:**
- ✅ Reflects current implementation (EPIC-002 complete)
- ✅ Clearly distinguishes implemented vs planned features
- ✅ Includes all required sections per specification
- ✅ Development rules for AI agents documented
- ✅ English language emphasized as PRIMARY and REFERENCE language

