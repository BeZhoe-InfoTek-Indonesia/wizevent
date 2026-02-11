# Architecture Overview: Filament Integration

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Event Ticket Management System                │
└─────────────────────────────────────────────────────────────────┘
                                 │
                ┌────────────────┴────────────────┐
                │                                 │
                ▼                                 ▼
    ┌───────────────────────┐       ┌───────────────────────┐
    │   ADMIN INTERFACE     │       │  VISITOR INTERFACE    │
    │   (Filament v3.x)     │       │  (Pure Livewire)      │
    │   ✅ WILL CHANGE      │       │  ❌ NO CHANGES        │
    └───────────────────────┘       └───────────────────────┘
                │                                 │
    ┌───────────┴───────────┐       ┌───────────┴───────────┐
    │ Routes: /admin/*      │       │ Routes: /             │
    │ Technology: Filament  │       │ Technology: Livewire  │
    │ Users: Admins only    │       │ Users: Visitors       │
    │ Purpose: CRUD mgmt    │       │ Purpose: Events/Tix   │
    └───────────────────────┘       └───────────────────────┘
                │                                 │
                └────────────────┬────────────────┘
                                 ▼
                    ┌────────────────────────┐
                    │  Shared Components     │
                    ├────────────────────────┤
                    │ • Laravel Breeze Auth  │
                    │ • Spatie Permission    │
                    │ • User Model           │
                    │ • Database             │
                    │ • Service Layer        │
                    │ • Tailwind CSS         │
                    └────────────────────────┘
```

## 📂 Folder Structure Impact

### ✅ WILL CHANGE (Admin Only)
```
app/
├── Filament/                    # NEW - Filament resources
│   ├── Resources/
│   │   ├── UserResource.php     # NEW - Replaces UserController
│   │   ├── RoleResource.php     # NEW - Replaces RoleController
│   │   └── PermissionResource.php
│   ├── Pages/
│   │   └── Dashboard.php        # NEW - Admin dashboard
│   └── Widgets/
│       └── StatsOverview.php    # NEW - Dashboard widgets

resources/views/
├── admin/                       # DEPRECATED - Old Blade views
│   ├── users/                   # Will be removed after migration
│   ├── roles/                   # Will be removed after migration
│   └── permissions/             # Will be removed after migration
```

### ❌ NO CHANGES (Visitor Interface)
```
app/
├── Livewire/                    # UNCHANGED - Visitor components
│   ├── Welcome.php
│   ├── Forms/
│   │   └── LoginForm.php
│   └── Profile/
│       └── UpdateProfileInformationForm.php

resources/views/
├── livewire/                    # UNCHANGED - Visitor views
│   └── pages/
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       └── welcome.blade.php
├── dashboard.blade.php          # UNCHANGED - Visitor dashboard
└── profile.blade.php            # UNCHANGED - Visitor profile
```

### 🔄 SHARED (Both Interfaces)
```
app/
├── Models/
│   └── User.php                 # UNCHANGED - Used by both
├── Services/
│   ├── ActivityLogger.php       # UNCHANGED - Used by both
│   └── UserService.php          # UNCHANGED - Used by both

config/
├── auth.php                     # MINOR UPDATE - Filament config
└── permission.php               # UNCHANGED - Spatie config

routes/
├── web.php                      # MINOR UPDATE - Filament routes
└── auth.php                     # UNCHANGED - Breeze routes
```

## 🎯 Interface Comparison

| Aspect | Admin Interface | Visitor Interface |
|--------|----------------|-------------------|
| **Framework** | ✅ Filament v3.x (NEW) | ❌ Livewire (UNCHANGED) |
| **Routes** | `/admin/*` | `/`, `/dashboard`, `/profile` |
| **Purpose** | User/Role/Permission CRUD | Events, Tickets, Profile |
| **Users** | Super Admin, Event Manager, Finance Admin, Check-in Staff | Visitors (end users) |
| **Technology** | Filament Resources + Livewire | Pure Livewire Components |
| **Views** | Auto-generated by Filament | Custom Blade + Livewire |
| **Authentication** | Laravel Breeze (shared) | Laravel Breeze (shared) |
| **Authorization** | Spatie Permission (shared) | Spatie Permission (shared) |
| **Styling** | Filament theme + Tailwind | Custom Tailwind |
| **Changes** | ✅ MAJOR REFACTOR | ❌ ZERO CHANGES |

## 🚀 Migration Strategy

### Phase 1: Admin Only (This Proposal)
- Install Filament for `/admin/*` routes
- Migrate admin CRUD to Filament Resources
- Keep visitor interface 100% unchanged

### Phase 2: Future (Separate EPICs)
- Build event management (visitor interface uses Livewire)
- Build ticketing system (visitor interface uses Livewire)
- Admin management of events uses Filament

## ✅ Guarantees

1. **Visitor interface will NOT be touched**
   - No changes to `/resources/views/livewire/`
   - No changes to `/app/Livewire/`
   - No changes to visitor routes
   - No changes to visitor authentication flow

2. **Shared components remain compatible**
   - User model works with both interfaces
   - Authentication system shared (Laravel Breeze)
   - Permission system shared (Spatie)
   - Service layer used by both

3. **Independent operation**
   - Admin interface can be updated without affecting visitor
   - Visitor interface can be updated without affecting admin
   - Both use same database and authentication

## 🔍 Example User Flows

### Admin User Flow (Filament)
```
1. Navigate to /admin
2. See Filament login page (if not authenticated)
3. Login via Laravel Breeze
4. See Filament dashboard with widgets
5. Click "Users" in Filament navigation
6. See Filament Users resource table
7. Click "Create" → Filament form
8. Submit → Filament handles CRUD
```

### Visitor User Flow (Livewire - UNCHANGED)
```
1. Navigate to /
2. See welcome page (Livewire)
3. Click "Login" → Livewire login form
4. Login via Laravel Breeze
5. See visitor dashboard (Livewire)
6. Click "Profile" → Livewire profile page
7. Update profile → Livewire handles update
```

---

**Key Takeaway**: Filament is **ONLY** for admin panel. Visitor interface stays **pure Livewire**.
