# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Clockobot is a time tracking application for freelancers built on Laravel 11 using the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire). It allows users to track time against estimates, create billable/non-billable entries, and generate customized reports.

**Tech Stack:**
- Laravel 11 (PHP 8.2+)
- Livewire 3 for reactive components
- Tailwind CSS for styling
- Alpine.js for frontend interactivity
- Filament Notifications for toast messages
- wire-elements/modal for modal management
- Maatwebsite/Excel for report exports
- Laravel Sanctum for API authentication

## Development Commands

### Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed  # Use --seed for dummy data
php artisan storage:link
```

### Running the Application
```bash
php artisan serve
npm run dev
```

### Testing
```bash
php artisan test                    # Run all tests
php artisan test --filter TestName  # Run specific test
php artisan test --coverage         # Run with coverage (100% target)
```

Tests use SQLite in-memory database (configured in phpunit.xml). Feature tests are in `tests/Feature`, unit tests in `tests/Unit`.

### Code Quality
```bash
./vendor/bin/pint          # Format code (Laravel Pint)
php artisan config:clear   # Clear config cache
php artisan cache:clear    # Clear application cache
```

### Building Assets
```bash
npm run build  # Production build
npm run dev    # Development build with hot reload
```

## Architecture

### Database Schema

**Core Models:**
- `User` - Users with `is_admin` flag for admin privileges
- `Client` - Clients that projects belong to
- `Project` - Projects with hour estimates and deadlines, belongs to Client
- `TimeEntry` - Time tracking entries with start/end times, belongs to User, Client, Project, and WorkType
- `WorkType` - Categories for time entries (e.g., development, design)

**Important Relationships:**
- TimeEntry uses `hasOne` relationships (not `belongsTo`) for client, project, work_type, and user
- Projects calculate `hours_consumption` as a percentage of hour_estimate
- TimeEntry has methods `calculateDurationInDecimal()` and `calculateDurationInHours()`

### Application Structure

**Controllers:**
- Web controllers in `app/Http/Controllers/` return views/Livewire components
- API controllers in `app/Http/Controllers/API/` handle JSON responses with Sanctum authentication
- Controllers are thin - they primarily route to Livewire components

**Livewire Components:**
- Index pages: `app/Livewire/{Resource}/{Resource}Index.php`
- Modals: `app/Livewire/Modals/{Resource}/` (Add, Edit, Delete)
- Special component: `TimerTrigger.php` manages the global timer for ongoing time entries

**Views:**
- Blade views in `resources/views/`
- Livewire component views in `resources/views/livewire/`
- Components in `resources/views/components/`

**Middleware:**
- `AdminMiddleware` - Restricts routes to users with `is_admin = true`
- `SetUserLocale` - Sets locale based on user preference

**Helpers:**
- Global helpers in `app/Helpers.php` (autoloaded via composer.json)
- `process_hours_total($entries)` - Calculates total hours from time entries
- `export_time_entries($entries)` - Exports entries to Excel

### API Routes

API uses Laravel Sanctum with throttling (50,000 requests per 1,000,000 minutes). All authenticated routes are in `routes/api.php` under `auth:api` middleware.

Key endpoints:
- Dashboard: `/api/dashboard/{hours,works,activities}`
- Resources: `/api/{clients,projects,work-types,time-entries,users}/list`
- CRUD: `/api/{resource}/{id}/{details,update,delete}`
- Reporting: `/api/reporting/{list,filter,export}`

### Configuration

**Environment Variables:**
- `APP_ALLOW_REGISTER` - Controls user registration (access via `config('app.allow_registration')`)
- Standard Laravel config for database, mail, cache, queue

**Mail Setup Required:**
- User registration sends password reset emails
- Configure MAIL_* variables before adding users

## Key Patterns

### Time Entry Duration Calculations
TimeEntry model has two duration methods:
- `calculateDurationInDecimal()` - Returns hours as float (e.g., 2.5)
- `calculateDurationInHours()` - Returns formatted string (e.g., "02:30")
- Both parse Carbon datetime objects from start/end timestamps

### Modal Pattern (wire-elements/modal)
Modals are Livewire components in `app/Livewire/Modals/`:
- AddModal - Creates new records
- EditModal - Updates existing records
- DeleteModal - Deletes records

### Timer System
`TimerTrigger` component manages active time entries:
- Creates TimeEntry with only `start` time (end is null)
- Listens for events: `startAutoTimer`, `refreshTimeTrigger`, `timerStopped`
- Disables timer if no clients, projects, or work types exist

### Admin-Only Routes
Routes in `routes/web.php` with `middleware(['admin'])` require `is_admin = true` on User model (checked by AdminMiddleware).

## Testing Notes

- Tests achieve 100% coverage (excluding files in phpunit.xml exclude list)
- Feature tests in `tests/Feature/Livewire/` test Livewire components
- API tests in `tests/Feature/API/` test API endpoints
- Uses factories for test data generation
- SQLite in-memory database for speed

## Git Workflow

- Main development branch: `develop`
- Bug fixes: Submit to latest `develop` (not main or tagged branches)
- New features: Base on `develop` branch
- Pull requests: Target `develop` for review

## Localization

Application supports multiple languages (de, en, es, fr):
- Language files in `lang/` directory
- User locale preference stored and applied via SetUserLocale middleware
- Use `__('key')` for translatable strings