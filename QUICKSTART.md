# Quick Start Guide - Running the Attendance Module

## Prerequisites

Before running the project, ensure you have:

- **PHP 7.4** or higher
- **Composer** installed
- **Node.js** and **NPM** installed
- **MySQL/PostgreSQL/SQLite** database server
- **Laravel 7.29** (already included in composer.json)

## Step-by-Step Setup

### 1. Check PHP Version

```bash
php -v
```

Should show PHP 7.4.x or higher.

### 2. Install PHP Dependencies

```bash
composer install
```

This installs Laravel and all required PHP packages.

### 3. Install Node Dependencies

```bash
npm install
```

This installs Laravel Mix and front-end build tools.

### 4. Create Environment File

If `.env` doesn't exist, create it:

```bash
# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

If `.env.example` doesn't exist, create a `.env` file manually with:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

This generates the `APP_KEY` in your `.env` file.

### 6. Configure Database

Edit `.env` and update database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**For SQLite (simpler for testing):**

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Then create the database file:
```bash
touch database/database.sqlite
```

### 7. Run Database Migrations

```bash
php artisan migrate
```

This creates all the required tables:
- `training_sessions`
- `attendances`
- `attendance_events`

### 8. Seed Sample Data

```bash
php artisan db:seed --class=TrainingSessionSeeder
```

This creates a sample hybrid training session.

### 9. Build Front-End Assets

**For development:**
```bash
npm run dev
```

**For production:**
```bash
npm run production
```

**For watch mode (auto-rebuild on changes):**
```bash
npm run watch
```

### 10. Start the Development Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

Or specify a custom port:
```bash
php artisan serve --port=8080
```

## Running Tests

To run the feature test:

```bash
php artisan test --filter AttendanceFlowTest
```

Or using PHPUnit directly:

```bash
vendor/bin/phpunit tests/Feature/AttendanceFlowTest.php
```

## Accessing the Application

### Trainee Check-In Page
```
http://localhost:8000/trainee/checkin
```

### Trainer Session View
```
http://localhost:8000/trainer/sessions/{session_id}
```

Replace `{session_id}` with the ID from the seeded session (usually `1`).

## Troubleshooting

### "Class not found" errors
```bash
composer dump-autoload
```

### "APP_KEY is not set" error
```bash
php artisan key:generate
```

### Database connection errors
- Verify database credentials in `.env`
- Ensure database server is running
- Check database exists (create it manually if needed)

### Asset compilation errors
```bash
npm install
npm run dev
```

### Permission errors (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### "Route not found" errors
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## Quick Commands Reference

```bash
# Clear all caches
php artisan optimize:clear

# View all routes
php artisan route:list

# Run migrations with fresh database
php artisan migrate:fresh --seed

# Start queue worker (if using queues)
php artisan queue:work
```

## Next Steps

1. Set up authentication for the `lms` guard
2. Create users in the database
3. Access the trainer session view to generate QR codes
4. Test the check-in flow using the trainee page

## Development Tips

- Use `npm run watch` during development for auto-recompilation
- Check `storage/logs/laravel.log` for errors
- Use `php artisan tinker` to interact with the database
- Enable debug mode in `.env` (`APP_DEBUG=true`) for detailed error messages


