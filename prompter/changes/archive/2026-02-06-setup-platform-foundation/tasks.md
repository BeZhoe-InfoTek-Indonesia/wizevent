## 1. Platform Core Setup
- [x] 1.1 Configure `composer.json` and `package.json` with all dependencies (Spatie, Livewire, Tailwind).
- [x] 1.2 Setup `vite.config.js` for asset compilation (Livewire/Tailwind).
- [x] 1.3 Configure `.env.example` and logging channel (daily rotation).
- [x] 1.4 Configure `phpunit.xml` and creating a base `Tests\TestCase`.

## 2. Database & Auth
- [x] 2.1 Configure MySQL connection in `.env` and `config/database.php`.
- [x] 2.2 Create migrations for Users (with fields: avatar, google_id).
- [x] 2.3 Install `spatie/laravel-permission` and publish migrations (Roles, Permissions).
- [x] 2.4 Create Seeder `RolePermissionSeeder` with core roles (Super Admin, Event Manager, Visitor, etc.).
- [x] 2.5 Configure `User` model (traits, fillables, relationships).
- [x] 2.6 Configure `Queue` using database driver (`jobs` schema).

## 3. Frontend Architecture
- [x] 3.1 Setup Tailwind CSS configuration (`tailwind.config.js`) with custom theme colors.
- [x] 3.2 Create Admin Layout (`resources/views/layouts/admin.blade.php`).
- [x] 3.3 Create Visitor Layout (`resources/views/layouts/visitor.blade.php`).
- [x] 3.4 Create rudimentary `DashboardController` for Admin.
- [x] 3.5 Create rudimentary `Welcome` Livewire component for Visitor.
- [x] 3.6 Define Routes in `web.php` separating `/admin` (auth, role:admin) and `/` (public/auth).

## 4. Service Layer Foundation
- [x] 4.1 Create `app/Services` directory.
- [x] 4.2 Create detailed `AGENTS.md` (or similar guide) or README entry explaining the Service Layer pattern.
- [x] 4.3 Configure PHPStan to analyze `app/Services`.

## 5. Verification
- [x] 5.1 Run `npm run build` to verify asset compilation.
- [x] 5.2 Run `php artisan migrate:fresh --seed` to verify DB and Seeding.
- [x] 5.3 Run `php artisan test` to verify test suite.
- [x] 5.4 Run `composer phpstan` to verify static analysis.
