# Laravel Attendance Module

A comprehensive attendance tracking system for Laravel 7.29 (PHP 7.4) that supports hybrid training sessions with ephemeral QR codes, remote fallbacks, keyword challenges, and presence beacons.

## Features

- **Hybrid Sessions**: Support for onsite, remote, and hybrid training sessions
- **Ephemeral QR Codes**: Secure QR codes with 45-second expiry
- **Geolocation Tracking**: GPS-based location verification with configurable radius
- **Keyword Challenges**: 6-character keyword verification stored in cache for 5 minutes
- **Presence Beacons**: Automatic heartbeat tracking every 2 minutes
- **Risk Scoring**: Automated risk assessment based on attendance patterns
- **Event Logging**: Complete audit trail of all attendance events

## Requirements

- PHP 7.4
- Laravel 7.29
- MySQL/PostgreSQL/SQLite
- Node.js and NPM (for asset compilation)

## Installation

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Environment Setup

Copy the `.env.example` file to `.env` and configure your database:

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Seed Database

```bash
php artisan db:seed --class=TrainingSessionSeeder
```

### 5. Build Assets

Compile the front-end assets using Laravel Mix:

```bash
npm run dev
```

Or for production:

```bash
npm run production
```

## Usage

### Creating a Training Session

Training sessions can be created programmatically:

```php
use App\Models\TrainingSession;
use Carbon\Carbon;

$session = TrainingSession::create([
    'lms_session_id' => \Illuminate\Support\Str::uuid()->toString(),
    'title' => 'Introduction to Laravel',
    'mode' => 'hybrid', // 'onsite', 'remote', or 'hybrid'
    'lat' => 40.7128,
    'lng' => -74.0060,
    'geo_radius_m' => 100,
    'starts_at' => Carbon::now()->addDays(1)->setTime(10, 0),
    'ends_at' => Carbon::now()->addDays(1)->setTime(12, 0),
]);
```

### Trainer Interface

Trainers can view sessions and generate QR codes at:

```
GET /trainer/sessions/{session}
```

Generate keyword challenges:

```
POST /trainer/sessions/{session}/challenge
```

### Trainee Check-In

Trainees can check in using:

1. **QR Code Scanning**: Scan the QR code displayed by the trainer
2. **Remote Join**: Join remotely without QR code
3. **Keyword Challenge**: Enter a 6-character keyword provided by the trainer

Check-in endpoint:

```
POST /attendance/check-in
```

Request body:
```json
{
    "token": "encrypted_qr_token",
    "mode": "onsite",
    "lat": 40.7128,
    "lng": -74.0060
}
```

### Presence Beacons

Once checked in, the system automatically sends presence beacons every 2 minutes to:

```
POST /sessions/{session}/beacon
```

### Keyword Challenge

Submit keyword challenge:

```
POST /sessions/{session}/challenge
```

Request body:
```json
{
    "keyword": "ABC123"
}
```

## Authentication

The module uses the `lms` authentication guard. Ensure your users are authenticated with this guard:

```php
Auth::guard('lms')->user();
```

## Testing

Run the feature test to verify the attendance flow:

```bash
php artisan test --filter AttendanceFlowTest
```

Or using PHPUnit directly:

```bash
vendor/bin/phpunit tests/Feature/AttendanceFlowTest.php
```

## Database Schema

### training_sessions
- `id`: Primary key
- `lms_session_id`: UUID (unique)
- `title`: Session title
- `mode`: Enum (onsite/remote/hybrid)
- `lat`, `lng`: Decimal coordinates (nullable)
- `geo_radius_m`: Integer (default: 100)
- `starts_at`, `ends_at`: Timestamps
- `timestamps`: Created/updated timestamps

### attendances
- `id`: Primary key
- `training_session_id`: Foreign key
- `lms_user_id`: UUID
- `mode`: Enum (onsite/remote)
- `geo_confidence`: Float (default: 0)
- `risk_score`: TinyInt (default: 0)
- `lat`, `lng`: Decimal coordinates (nullable)
- `ip_hash`: String (nullable)
- `checked_in_at`, `check_out_at`, `challenge_passed_at`, `last_beacon_at`: Timestamps (nullable)
- `flags`: JSON (nullable)
- `timestamps`: Created/updated timestamps
- Unique constraint on `(training_session_id, lms_user_id)`

### attendance_events
- `id`: Primary key
- `attendance_id`: Foreign key
- `type`: Enum (check_in/challenge/beacon/check_out/flag)
- `payload`: JSON (nullable)
- `timestamps`: Created/updated timestamps

## Front-End JavaScript

The attendance module includes a JavaScript module at `resources/js/modules/attendance.js` that handles:

- QR code scanning using qr-scanner library
- Geolocation requests
- Check-in submissions
- Automatic beacon heartbeats
- Keyword challenge flow

To use it, include the compiled script in your Blade templates:

```blade
<script src="{{ mix('js/modules/attendance.js') }}"></script>
```

## Session Activity

Sessions are considered active 15 minutes before the start time and 30 minutes after the end time. This window allows for early check-ins and late submissions.

## Security Features

- **Ephemeral QR Codes**: 45-second expiry prevents token reuse
- **IP Hashing**: IP addresses are hashed for privacy
- **Geolocation Verification**: Optional GPS-based location checks
- **Risk Scoring**: Automated risk assessment based on attendance patterns
- **Event Logging**: Complete audit trail of all actions

## Troubleshooting

### QR Code Not Scanning
- Ensure the QR code hasn't expired (45 seconds)
- Check camera permissions in the browser
- Verify qr-scanner library is loaded

### Geolocation Not Working
- Check browser permissions for location access
- Ensure HTTPS is used (required for geolocation in most browsers)
- Verify GPS is enabled on mobile devices

### Authentication Issues
- Ensure users are authenticated with the `lms` guard
- Check that the `lms` guard is properly configured in `config/auth.php`

## License

This module is part of a Laravel application and follows the same license as the parent project.
