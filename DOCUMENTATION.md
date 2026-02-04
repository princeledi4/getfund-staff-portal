# GetFund USSD Staff Portal - Documentation

## Table of Contents
- [Overview](#overview)
- [Features](#features)
- [Architecture](#architecture)
- [Database Schema](#database-schema)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [API Reference](#api-reference)
- [Development](#development)
- [Security](#security)
- [Troubleshooting](#troubleshooting)

---

## Overview

**GetFund USSD Staff Portal** is a Laravel-based staff directory and profile management system designed for the GetFund organization (getfund.gov.gh). The application provides a secure administrative panel for managing staff and department information, along with a public portal for staff profile lookups.

### Technology Stack
- **Backend:** Laravel 10.28.0 (PHP 8.1+)
- **Admin Panel:** Filament 3.0
- **Frontend:** Tailwind CSS 3.3.3, Alpine.js, Livewire 3.x
- **Database:** MySQL
- **Build Tool:** Vite 4.0.0

### Project Information
- **Developer:** GETFund
- **Organization:** GetFund (getfund.gov.gh)
- **Framework:** Laravel 10.x
- **License:** MIT

---

## Features

### Public Portal
- **Staff Search:** Real-time lookup by unique staff ID
- **Profile Display:** View comprehensive staff profiles with photos
- **Responsive Design:** Mobile-friendly interface with dark mode support

### Admin Panel (Filament)
- **Staff Management:**
  - Full CRUD operations (Create, Read, Update, Delete)
  - Photo upload with image editor (max 1MB)
  - Searchable and filterable staff list
  - Department-based filtering
  - Bulk operations support

- **Department Management:**
  - Create and manage organizational departments
  - View all staff members within each department
  - Inline staff management from department view

- **Dashboard:**
  - Total staff count widget
  - Total departments count widget
  - Quick statistics overview

- **Security:**
  - Email-based authentication
  - Email verification required
  - Organization-specific access (@getfund.gov.gh only)
  - CSRF protection

---

## Architecture

### Application Structure

```
GetFund USSD Portal
├── Public Portal (/)
│   ├── Staff Search (Livewire Component)
│   └── Staff Profile Display
│
├── Admin Panel (/admin)
│   ├── Dashboard
│   ├── Staff Management
│   ├── Department Management
│   └── User Profile
│
└── API (/api)
    └── User Authentication (Sanctum)
```

### Design Patterns
- **MVC Architecture:** Separation of concerns
- **Repository Pattern:** Filament resources
- **Single Responsibility:** Invokable controllers
- **Service Provider Pattern:** Dependency injection
- **Eloquent ORM:** Database abstraction

### Key Components

#### Models
1. **User** (`app/Models/User.php`)
   - Admin user accounts
   - Filament panel access control
   - Email verification

2. **Staff** (`app/Models/Staff.php`)
   - Staff member information
   - Belongs to Department
   - Full name accessor

3. **Department** (`app/Models/Department.php`)
   - Organizational departments
   - Has many Staff members

#### Controllers
1. **ShowStaffProfileController** (`app/Http/Controllers/ShowStaffProfileController.php`)
   - Invokable controller
   - Displays staff profile by staff ID
   - Eager loads department relationship

#### Livewire Components
1. **Staff Search** (`app/Livewire/Staff/Search.php`)
   - Real-time staff ID validation
   - Database query on submit
   - Redirect to profile or show error

#### Filament Resources
1. **StaffResource** - Complete staff CRUD interface
2. **DepartmentResource** - Department management with relation manager

---

## Database Schema

### Tables Overview

#### users
Admin user accounts for Filament panel access.

| Column | Type | Attributes |
|--------|------|------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(255) | |
| email | VARCHAR(255) | UNIQUE |
| email_verified_at | TIMESTAMP | NULLABLE |
| password | VARCHAR(255) | |
| remember_token | VARCHAR(100) | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### departments
Organizational departments.

| Column | Type | Attributes |
|--------|------|------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT |
| name | VARCHAR(255) | |
| slug | VARCHAR(255) | NULLABLE |
| description | LONGTEXT | NULLABLE |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

#### staff
Individual staff member records.

| Column | Type | Attributes |
|--------|------|------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT |
| first_name | VARCHAR(255) | |
| middle_name | VARCHAR(255) | NULLABLE |
| last_name | VARCHAR(255) | |
| staff_id | VARCHAR(255) | UNIQUE |
| position | VARCHAR(255) | NULLABLE |
| photo | VARCHAR(255) | NULLABLE |
| phone_number | VARCHAR(16) | NULLABLE |
| email | VARCHAR(255) | NULLABLE |
| department_id | BIGINT | FOREIGN KEY → departments.id |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Relationships

```
Department (1) ─┬─ (Many) Staff
                │
                └─ Cascade: No (manual management)
```

---

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7 or higher
- Laravel Herd (optional but recommended for Windows)

### Step 1: Clone & Install Dependencies

```bash
# Clone the repository
git clone <repository-url> getfund-ussd
cd getfund-ussd

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 2: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=getfund_ussd
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Database Setup

```bash
# Create database (if not exists)
mysql -u root -p -e "CREATE DATABASE getfund_ussd;"

# Run migrations
php artisan migrate

# Seed default admin user
php artisan db:seed
```

**Default Admin Credentials:**
- Email: `admin@getfund.gov.gh`
- Password: `password`

**IMPORTANT:** Change the default password after first login!

### Step 4: Storage Setup

```bash
# Create symbolic link for public storage access
php artisan storage:link
```

### Step 5: Build Frontend Assets

```bash
# Development build with hot reload
npm run dev

# OR production build
npm run build
```

### Step 6: Run the Application

**Option A: Laravel Herd (Recommended for Windows)**
- Herd automatically serves Laravel applications
- Access at: `http://getfund-ussd.test`

**Option B: Artisan Development Server**
```bash
php artisan serve
# Access at: http://localhost:8000
```

### Step 7: Access the Admin Panel

1. Navigate to `/admin` (e.g., `http://localhost:8000/admin`)
2. Login with default credentials
3. Change password in profile settings
4. Start adding departments and staff

---

## Usage

### For End Users (Public Portal)

#### Searching for Staff
1. Visit the homepage (`/`)
2. Enter the staff member's unique Staff ID
3. Click "Search"
4. View the staff profile with photo, contact details, and department

### For Administrators

#### Accessing the Admin Panel
1. Navigate to `/admin`
2. Login with your @getfund.gov.gh email
3. Verify your email if required

#### Managing Departments
1. Click "Departments" in the sidebar
2. Click "New Department" to create
3. Enter department name and optional description
4. Click "Create" to save

#### Managing Staff
1. Click "Staff" in the sidebar
2. Click "New Staff" to add a staff member
3. Fill in the required fields:
   - First Name, Last Name
   - Unique Staff ID
   - Position
   - Department (searchable dropdown)
   - Photo (max 1MB)
   - Optional: Middle Name, Email, Phone
4. Click "Create" to save

#### Viewing Staff Profiles
- Click any staff member in the list to view details
- From department view, see all related staff
- Use filters to narrow by department

#### Editing Staff
1. Find the staff member in the list
2. Click the "Edit" action
3. Modify fields as needed
4. Click "Save" to update

#### Deleting Records
- Click the "Delete" action on any record
- Confirm deletion
- Bulk delete: Select multiple records and choose "Delete selected"

---

## File Structure

### Important Directories

```
getfund-ussd/
├── app/
│   ├── Filament/              # Filament admin resources
│   │   ├── Resources/         # Staff & Department CRUD
│   │   └── Widgets/           # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/       # Route controllers
│   │   └── Middleware/        # HTTP middleware
│   ├── Livewire/              # Livewire components
│   │   └── Staff/Search.php   # Staff search component
│   ├── Models/                # Eloquent models
│   └── Providers/             # Service providers
├── config/                     # Configuration files
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/
│   ├── assets/media/logo/     # Application logos
│   └── build/                 # Compiled frontend assets
├── resources/
│   ├── css/                   # CSS source files
│   ├── js/                    # JavaScript source files
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
└── storage/
    ├── app/staff/             # Uploaded staff photos
    └── logs/                  # Application logs
```

### Key Files

| File | Purpose |
|------|---------|
| `app/Models/Staff.php` | Staff model with relationships |
| `app/Models/Department.php` | Department model |
| `app/Filament/Resources/StaffResource.php` | Admin staff management |
| `app/Livewire/Staff/Search.php` | Public staff search |
| `resources/views/welcome.blade.php` | Public portal home |
| `resources/views/staff-details.blade.php` | Staff profile display |
| `routes/web.php` | Public web routes |
| `.env` | Environment configuration |

---

## API Reference

### Authentication
All API routes use Laravel Sanctum for token-based authentication.

### Available Endpoints

#### Get Authenticated User
```http
GET /api/user
Authorization: Bearer {token}
```

**Response:**
```json
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@getfund.gov.gh",
  "email_verified_at": "2023-09-11T12:00:00.000000Z",
  "created_at": "2023-09-11T12:00:00.000000Z",
  "updated_at": "2023-09-11T12:00:00.000000Z"
}
```

### Web Routes

#### Homepage
```http
GET /
```
Displays the staff search portal.

#### Staff Profile
```http
GET /staff/{staff_id}/profile
```

**Parameters:**
- `staff_id` (string): Unique staff identifier

**Returns:** Staff profile page or 404 if not found

---

## Development

### Running in Development Mode

```bash
# Start PHP development server
php artisan serve

# Start Vite dev server (in another terminal)
npm run dev
```

### Code Style
This project uses Laravel Pint for code formatting.

```bash
# Format code
./vendor/bin/pint
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/StaffTest.php

# Run with coverage
php artisan test --coverage
```

### Database Management

```bash
# Create new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (drop all tables)
php artisan migrate:fresh

# Seed database
php artisan db:seed
```

### Creating New Admin Users

**Method 1: Database Seeder**
Edit `database/seeders/UserSeeder.php` and run:
```bash
php artisan db:seed --class=UserSeeder
```

**Method 2: Tinker**
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'New Admin',
    'email' => 'newadmin@getfund.gov.gh',
    'password' => bcrypt('password'),
    'email_verified_at' => now()
]);
```

**Method 3: Registration**
- Navigate to `/admin/register`
- Use an @getfund.gov.gh email
- Verify email via link

### Clearing Cache

```bash
# Clear all caches
php artisan optimize:clear

# Or individually:
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Security

### Access Control

#### Admin Panel Access Requirements
1. Email must end with `@getfund.gov.gh`
2. Email must be verified
3. User account must exist in database

Implementation in `app/Models/User.php`:
```php
public function canAccessPanel(Panel $panel): bool
{
    return str_ends_with($this->email, '@getfund.gov.gh')
        && $this->hasVerifiedEmail();
}
```

### Security Best Practices

#### Production Deployment Checklist
- [ ] Change `APP_ENV` to `production`
- [ ] Set `APP_DEBUG` to `false`
- [ ] Generate new `APP_KEY`
- [ ] Use strong database passwords
- [ ] Change default admin password
- [ ] Configure proper file permissions (755 for directories, 644 for files)
- [ ] Enable HTTPS
- [ ] Set up regular database backups
- [ ] Configure proper CORS settings
- [ ] Review and restrict `.env` access
- [ ] Set up firewall rules

#### File Upload Security
- Maximum file size: 1MB
- Allowed file types: Images only
- Storage location: `storage/app/staff/` (not publicly accessible without link)
- Validation: Filament's built-in image validation

### CSRF Protection
All web routes are protected by Laravel's CSRF middleware. Ensure forms include:
```blade
@csrf
```

---

## Troubleshooting

### Common Issues

#### Issue: "Class 'Storage' not found"
**Solution:**
```php
use Illuminate\Support\Facades\Storage;
```

#### Issue: Staff photos not displaying
**Solution:**
```bash
# Ensure storage link exists
php artisan storage:link

# Check file permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### Issue: Cannot access admin panel
**Possible Causes:**
1. Email not from @getfund.gov.gh domain
2. Email not verified
3. User doesn't exist in database

**Solution:**
- Check user email domain
- Verify email via link
- Create user via seeder or registration

#### Issue: Vite manifest not found
**Solution:**
```bash
npm install
npm run build
```

#### Issue: Database connection error
**Solution:**
- Verify `.env` database credentials
- Ensure MySQL service is running
- Check database exists: `CREATE DATABASE getfund_ussd;`

#### Issue: Permission denied on storage
**Solution:**
```bash
# Windows (Git Bash/WSL)
chmod -R 775 storage bootstrap/cache

# Or recursively change owner
chown -R www-data:www-data storage bootstrap/cache
```

#### Issue: Livewire component not working
**Solution:**
- Clear cache: `php artisan optimize:clear`
- Ensure `@livewireScripts` and `@livewireStyles` in layout
- Check browser console for errors

### Debug Mode

Enable debug mode in `.env` for detailed error messages:
```env
APP_DEBUG=true
```

**NEVER enable debug mode in production!**

### Logs

Check application logs for errors:
```bash
# View logs
tail -f storage/logs/laravel.log

# Or on Windows
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

---

## Maintenance

### Backup Procedures

#### Database Backup
```bash
# Export database
mysqldump -u root -p getfund_ussd > backup.sql

# Import database
mysql -u root -p getfund_ussd < backup.sql
```

#### File Backup
```bash
# Backup staff photos
tar -czf staff-photos-backup.tar.gz storage/app/staff/

# Backup entire application
tar -czf getfund-ussd-backup.tar.gz \
  --exclude=node_modules \
  --exclude=vendor \
  --exclude=storage/logs \
  .
```

### Update Procedures

```bash
# Update composer dependencies
composer update

# Update npm dependencies
npm update

# Run migrations
php artisan migrate

# Clear and rebuild cache
php artisan optimize:clear
php artisan optimize
```

---

## Future Development Recommendations

### Suggested Enhancements

1. **USSD Integration**
   - Implement SMS gateway (e.g., Africa's Talking)
   - Add USSD session management
   - Create USSD menu navigation

2. **Advanced Features**
   - Role-based access control (Super Admin, Department Head)
   - Staff status (Active/Inactive/On Leave)
   - Staff directory export (PDF/CSV)
   - Audit logging for admin actions
   - Email notifications for new staff
   - Advanced search (by name, department, position)

3. **API Expansion**
   - RESTful API for mobile apps
   - API documentation (Swagger/OpenAPI)
   - Rate limiting
   - API versioning

4. **Performance**
   - Redis caching
   - Database query optimization
   - Image optimization
   - CDN for assets

5. **Cloud Integration**
   - AWS S3 for file storage
   - Cloud database (RDS)
   - Email service (SES, SendGrid)

6. **Security Enhancements**
   - Two-factor authentication
   - Activity logging
   - IP whitelisting for admin
   - Automated security scans

---

## Support & Contact

### Getting Help
- Check this documentation first
- Review Laravel documentation: https://laravel.com/docs
- Review Filament documentation: https://filamentphp.com/docs

### Development Team
- **Developer:** Ghana Education Trust Fund
- **Organization:** GetFund (getfund.gov.gh)

---

## License

This project is based on the Laravel framework, which is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Last Updated:** 2025-10-28
**Version:** 1.0.0
**Documentation Maintainer:** Claude Code
