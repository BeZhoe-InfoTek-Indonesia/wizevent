# Event Ticket Management System

A comprehensive Laravel-based SaaS platform for event creation, ticket distribution, payment processing, and real-time QR code-based venue access control.

## 🚀 Features

- **Event Management**: Create and manage events with wizard-based configuration
- **Ticketing System**: Multiple ticket types with dynamic pricing and inventory control
- **Payment Processing**: Secure payment verification workflow with proof upload
- **Digital Tickets**: PDF ticket generation with secure QR codes
- **Admin Panel**: Full-featured Filament v4 admin interface
- **User Management**: Role-based access control with 5 predefined roles
- **Multi-language**: English and Indonesian support
- **AI-Powered Planning**: Event planning with AI budget forecasting and risk assessment
- **CMS System**: Banner management, FAQs, and content pages
- **PWA Support**: Progressive Web App capabilities for mobile users

## 📋 System Requirements

- **PHP**: 8.2 or higher
- **Composer**: 2.x
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Apache or Nginx
- **Extensions**: 
  - OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath
  - GD Extension (for image processing)
  - Zip Extension (for PDF generation)

## 🔧 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd event-management
```

### 2. Install Dependencies

Install PHP dependencies:
```bash
composer install
```

Install Node.js dependencies:
```bash
npm install
```

### 3. Environment Configuration

Copy the example environment file:
```bash
cp .env.example .env
```

Edit `.env` and configure the following settings:

```env
APP_NAME="Event Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration (for email notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth (optional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# AI Services (optional - for event planning features)
GEMINI_API_KEY=
OPENAI_API_KEY=
```

Generate application key:
```bash
php artisan key:generate
```

### 4. Database Setup

Create a new database and run migrations:
```bash
php artisan migrate
```

Seed the database with initial data (roles, permissions, etc.):
```bash
php artisan db:seed
```

### 5. Link Storage

Create symbolic link for public storage:
```bash
php artisan storage:link
```

### 6. Build Assets

Compile frontend assets:
```bash
npm run build
```

For development (with hot module replacement):
```bash
npm run dev
```

### 7. Generate IDE Helper Files (Optional)

For better IDE support (autocomplete, type hints):
```bash
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
```

### 8. Start Development Server

Run the Laravel development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

**Access Points:**
- **Admin Panel**: `http://localhost:8000/admin`
- **Visitor Interface**: `http://localhost:8000`

### 9. Create Admin User

After installation, you'll need to create an admin user. You can do this via:

**Option 1: Register through the UI**
1. Visit `http://localhost:8000/register`
2. Create a new account
3. Assign Super Admin role via database or tinker:

```bash
php artisan tinker
```

```php
use App\Models\User;
use Spatie\Permission\Models\Role;

$user = User::find(1);
$user->assignRole('Super Admin');
```

**Option 2: Create via Tinker**

```bash
php artisan tinker
```

```php
use App\Models\User;
use Spatie\Permission\Models\Role;

$user = User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
]);

$user->assignRole('Super Admin');
```

## 📁 Project Structure

```
event-management/
├── app/
│   ├── Console/           # Artisan commands
│   ├── Filament/          # Admin panel resources and pages
│   ├── Http/              # Controllers and middleware
│   ├── Jobs/              # Queue jobs
│   ├── Livewire/          # Livewire components
│   ├── Mail/              # Email templates
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic layer
├── config/                # Configuration files
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── lang/                  # Translation files (en, id)
├── public/                # Public assets
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
└── routes/                # Route definitions
```

## 👥 User Roles & Permissions

The system includes 5 predefined roles:

1. **Super Admin**: Full system access
2. **Event Manager**: Event and ticket management
3. **Finance Admin**: Payment verification and financial reporting
4. **Check-in Staff**: QR scanning and ticket validation
5. **Visitor**: Event browsing and ticket purchase

## 🔐 Security Features

- Role-based access control (Spatie Permission)
- Activity logging for audit trails
- CSRF protection
- SQL injection prevention
- XSS protection
- Secure password hashing (bcrypt)
- Encrypted QR codes (AES-256-CBC)

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Run with coverage:
```bash
php artisan test --coverage
```

Run static analysis (PHPStan):
```bash
composer phpstan
```

## 📚 Documentation

For detailed documentation, see:
- [`docs/AGENTS.md`](docs/AGENTS.md) - Complete project knowledge base
- [`docs/requirement.md`](docs/requirement.md) - Full requirements specification
- [`docs/admin-interface-guide.md`](docs/admin-interface-guide.md) - Admin panel guide
- [`docs/permissions-and-roles.md`](docs/permissions-and-roles.md) - Permission system details

## 🛠️ Development Tools

### Queue Worker

For processing background jobs (emails, PDF generation, etc.):

```bash
php artisan queue:work
```

### Cache Management

Clear all caches:
```bash
php artisan optimize:clear
```

### Permission Cache

Reset permission cache:
```bash
php artisan permission:cache-reset
```

### Activity Log Cleanup

Clean old activity logs (runs daily via scheduler):
```bash
php artisan activitylog:clean
```

## 🐛 Troubleshooting

### Permission Issues

If you encounter permission issues, run:
```bash
php artisan permission:cache-reset
php artisan shield:generate --all
```

### Filament Admin Panel Not Accessible

1. Check user has admin role:
```bash
php artisan tinker
User::find(1)->roles
```

2. Clear caches:
```bash
php artisan optimize:clear
php artisan filament:cache-clear
```

### Assets Not Loading

1. Clear and rebuild assets:
```bash
npm run build
php artisan view:clear
```

2. Check storage link:
```bash
php artisan storage:link
```

## 📄 License

This project is proprietary software. All rights reserved.

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation
4. Run `composer phpstan` before committing
5. Follow the existing code style and patterns

## 📞 Support

For support and questions:
- Check the documentation in the `docs/` directory
- Review the troubleshooting section above
- Check Laravel logs: `storage/logs/laravel.log`

---

**Built with ❤️ using Laravel 11, Filament v4, and Livewire**
