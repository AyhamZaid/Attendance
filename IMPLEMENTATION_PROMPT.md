# Attendance System - Implementation Prompt

## Project Overview

Build a comprehensive attendance tracking system for Laravel 7.29 that supports hybrid training sessions with multiple check-in methods, geolocation verification, and real-time presence monitoring.

## Core Requirements

### 1. Training Session Management
- Support three session modes: `onsite`, `remote`, and `hybrid`
- Each session must have:
  - Unique LMS session ID (UUID)
  - Title
  - Start and end timestamps
  - Optional geolocation (latitude, longitude, radius in meters)
  - Session mode (onsite/remote/hybrid)

### 2. QR Code Check-In System
- Generate ephemeral QR codes that expire after 45 seconds
- Support separate QR codes for onsite and remote modes
- QR codes must contain encrypted tokens with:
  - Session ID
  - Mode (onsite/remote)
  - Expiration timestamp
- QR codes should be displayed on trainer dashboard
- Trainees scan QR codes to check in

### 3. Multiple Check-In Methods
- **QR Code Scanning:** Primary method for onsite/remote check-in
- **Keyword Challenge:** 6-character random keyword (5-minute expiry)
- **Remote Join:** Direct remote check-in without QR code

### 4. Geolocation Verification
- Optional GPS-based location verification for onsite check-ins
- Configurable radius (default: 100 meters)
- Store latitude, longitude, and confidence score
- Hash IP addresses for privacy

### 5. Presence Monitoring
- Automatic heartbeat beacons every 2 minutes after check-in
- Track last beacon timestamp
- Risk scoring based on attendance patterns
- Event logging for audit trail

### 6. Security Features
- Ephemeral QR codes (45-second expiry prevents reuse)
- Encrypted tokens using Laravel encryption
- IP address hashing
- Risk scoring algorithm
- Complete event audit trail

## Technical Stack

### Backend
- **Framework:** Laravel 7.29
- **PHP Version:** 7.4+
- **Database:** MySQL/PostgreSQL/SQLite
- **Authentication:** Laravel Auth with custom `lms` guard

### Frontend
- **CSS Framework:** Bootstrap 5.1.3
- **QR Code Library:** qrcode.js (npm package, version 1.5.3+)
- **QR Scanner:** qr-scanner or jsQR for trainee side
- **JavaScript:** Vanilla JS (no framework required)

## Database Schema

### training_sessions
```sql
- id (primary key)
- lms_session_id (UUID, unique)
- title (string)
- mode (enum: 'onsite', 'remote', 'hybrid')
- lat (decimal 10,7, nullable)
- lng (decimal 10,7, nullable)
- geo_radius_m (unsigned integer, default: 100)
- starts_at (timestamp)
- ends_at (timestamp)
- timestamps (created_at, updated_at)
```

### attendances
```sql
- id (primary key)
- training_session_id (foreign key)
- lms_user_id (UUID string)
- mode (enum: 'onsite', 'remote')
- geo_confidence (float, default: 0)
- risk_score (tinyint, default: 0)
- lat (decimal 10,7, nullable)
- lng (decimal 10,7, nullable)
- ip_hash (string, nullable)
- checked_in_at (timestamp, nullable)
- check_out_at (timestamp, nullable)
- challenge_passed_at (timestamp, nullable)
- last_beacon_at (timestamp, nullable)
- flags (JSON, nullable)
- timestamps (created_at, updated_at)
- UNIQUE constraint on (training_session_id, lms_user_id)
```

### attendance_events
```sql
- id (primary key)
- attendance_id (foreign key)
- type (enum: 'check_in', 'challenge', 'beacon', 'check_out', 'flag')
- payload (JSON, nullable)
- timestamps (created_at, updated_at)
```

## API Endpoints

### Trainer Routes (Authenticated: `auth:lms`)
```
GET  /trainer/sessions              - List all sessions (redirects to first)
GET  /trainer/sessions/{session}    - View session with QR codes
POST /trainer/sessions/{session}/challenge - Generate keyword challenge
```

### Attendance Routes (Authenticated: `auth:lms`, Middleware: `risk.context`)
```
POST /attendance/check-in           - Check in with QR token
POST /sessions/{session}/challenge  - Submit keyword challenge
POST /sessions/{session}/beacon     - Send presence beacon
```

### Public Routes
```
GET  /trainee/checkin               - Trainee check-in page
GET  /login                          - Login page
POST /login                          - Login handler
POST /logout                         - Logout handler
GET  /register                       - Registration page
POST /register                       - Registration handler
```

## Implementation Steps

### Phase 1: Database Setup

1. **Create Migrations:**
   - `create_training_sessions_table.php`
   - `create_attendances_table.php`
   - `create_attendance_events_table.php`

2. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

3. **Create Seeders:**
   - `TrainingSessionSeeder` - Sample hybrid session
   - `UserSeeder` - Test users (optional)

### Phase 2: Models & Relationships

1. **Create Models:**
   - `TrainingSession` (with date casting for starts_at/ends_at)
   - `Attendance` (with relationships to TrainingSession)
   - `AttendanceEvent` (with relationship to Attendance)

2. **Model Relationships:**
   ```php
   TrainingSession hasMany Attendance
   Attendance belongsTo TrainingSession
   Attendance hasMany AttendanceEvent
   AttendanceEvent belongsTo Attendance
   ```

### Phase 3: Services

1. **QrTokenService:**
   ```php
   - generate(array $payload): string
     // Encrypts payload with 45-second expiry
   - validate(string $token): ?array
     // Decrypts and validates token, checks expiry
   ```

2. **Risk Scoring Service (optional):**
   - Calculate risk scores based on:
     - Geolocation mismatch
     - Missing beacons
     - Check-in patterns

### Phase 4: Controllers

1. **TrainerSessionController:**
   - `show(TrainingSession $session)` - Display session with QR codes
   - `challenge(TrainingSession $session)` - Generate keyword challenge

2. **AttendanceController:**
   - `checkIn(CheckInRequest $request)` - Process QR code check-in
   - `submitChallenge(ChallengeRequest $request)` - Validate keyword
   - `beacon(BeaconRequest $request)` - Update presence beacon

### Phase 5: Request Validation

1. **CheckInRequest:**
   - `token` (required, string)
   - `mode` (required, enum: onsite/remote)
   - `lat`, `lng` (nullable, numeric)
   - `ip` (auto-captured)

2. **ChallengeRequest:**
   - `keyword` (required, string, length: 6)
   - `session_id` (from route)

3. **BeaconRequest:**
   - `lat`, `lng` (nullable, numeric)
   - `session_id` (from route)

### Phase 6: Middleware

1. **EnsureSessionIsActive:**
   - Check if session is active (15 min before start, 30 min after end)
   - Return 403 if session not active

2. **AttachRiskContext:**
   - Calculate and attach risk context to request
   - Store in request attributes for controllers

### Phase 7: Frontend - Trainer Dashboard

1. **Session View Page** (`trainer/sessions/show.blade.php`):
   - Display session information
   - Show QR codes for onsite/remote (based on mode)
   - Keyword challenge generator button
   - QR codes generated client-side using qrcode.js

2. **QR Code Component** (`components/qr-panel.blade.php`):
   - Reusable component for QR code display
   - Uses data attributes for token and mode
   - Renders canvas element for QR code

3. **JavaScript Implementation:**
   - Load qrcode.js from CDN (with fallbacks)
   - Generate QR codes using `QRCode.toCanvas()`
   - Handle errors gracefully
   - Auto-refresh QR codes (they expire after 45 seconds)

### Phase 8: Frontend - Trainee Check-In

1. **Check-In Page** (`trainee/checkin.blade.php`):
   - QR code scanner interface
   - Remote join option
   - Keyword challenge input
   - Status messages and error handling

2. **JavaScript Module** (`resources/js/modules/attendance.js`):
   - QR code scanning using qr-scanner or jsQR
   - Geolocation API integration
   - Check-in form submission
   - Automatic beacon heartbeats (every 2 minutes)
   - Keyword challenge flow

### Phase 9: Authentication

1. **Custom Auth Guard:**
   - Configure `lms` guard in `config/auth.php`
   - Use existing `users` table
   - Update `LoginController` to use `lms` guard

2. **User Model:**
   - Ensure `lms_id` field exists (or use deterministic UUID generation)
   - Support for LMS user ID mapping

### Phase 10: Security Implementation

1. **Token Encryption:**
   - Use Laravel's `Crypt::encryptString()` for QR tokens
   - Include expiration timestamp in payload
   - Validate expiration on check-in

2. **IP Hashing:**
   - Hash IP addresses using `hash('sha256', $ip)`
   - Store hashed IPs only (never plain text)

3. **Risk Scoring:**
   - Calculate risk based on:
     - Geolocation distance from expected location
     - Missing beacons
     - Check-in timing anomalies
   - Store risk_score (0-100) in attendance record

4. **Event Logging:**
   - Log all attendance events:
     - check_in
     - challenge
     - beacon
     - check_out
     - flag (manual flags)
   - Store event payload as JSON

## Key Implementation Details

### QR Code Token Structure
```php
$payload = [
    'session_id' => $session->id,
    'mode' => 'onsite', // or 'remote'
    'expires_at' => Carbon::now()->addSeconds(45)->timestamp
];
$token = Crypt::encryptString(json_encode($payload));
```

### Session Activity Window
- Sessions are considered "active" 15 minutes before start time
- Sessions remain active 30 minutes after end time
- This allows early check-ins and late submissions

### Geolocation Verification
```php
// Calculate distance between two coordinates
function calculateDistance($lat1, $lng1, $lat2, $lng2) {
    // Haversine formula implementation
    // Returns distance in meters
}

// Check if within radius
$distance = calculateDistance($session->lat, $session->lng, $request->lat, $request->lng);
$geo_confidence = ($distance <= $session->geo_radius_m) ? 1.0 : 0.0;
```

### Presence Beacons
- Automatically sent every 2 minutes after check-in
- Update `last_beacon_at` timestamp
- Missing beacons increase risk score
- Beacon stops if user checks out

### Keyword Challenge Flow
1. Trainer clicks "Generate Keyword" button
2. Backend generates 6-character random uppercase string
3. Stored in cache for 5 minutes: `session:{id}:challenge`
4. Trainer displays keyword to trainees
5. Trainees enter keyword on check-in page
6. Backend validates against cache
7. Cache entry deleted after successful validation

## Frontend Dependencies

### Package.json
```json
{
  "dependencies": {
    "qr-scanner": "^1.4.2"
  }
}
```

### CDN Scripts (in Blade templates)
```html
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- QR Code Generator (Trainer) -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<!-- QR Code Scanner (Trainee) -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
```

## Testing Requirements

### Unit Tests
- QrTokenService: generate and validate methods
- Token expiration validation
- Geolocation distance calculations

### Feature Tests
- Complete attendance flow:
  1. Create session
  2. Generate QR code
  3. Check in with QR code
  4. Send beacons
  5. Check out

### Integration Tests
- Session activity middleware
- Risk scoring calculations
- Event logging

## Deployment Considerations

1. **Environment Variables:**
   - `APP_KEY` must be set (for encryption)
   - Database credentials
   - Session driver configuration

2. **File Permissions:**
   - `storage/` and `bootstrap/cache/` must be writable

3. **CDN Reliability:**
   - Consider hosting qrcode.js locally for production
   - Implement fallback mechanisms

4. **HTTPS Required:**
   - Geolocation API requires HTTPS
   - Encryption requires secure connection

## Success Criteria

✅ QR codes generate and display correctly for all session modes
✅ QR codes expire after 45 seconds and refresh automatically
✅ Check-in works via QR code scanning
✅ Keyword challenge flow works end-to-end
✅ Presence beacons send automatically every 2 minutes
✅ Geolocation verification works for onsite check-ins
✅ Risk scoring calculates correctly
✅ All events are logged in attendance_events table
✅ Session activity middleware enforces time windows
✅ Error handling provides clear user feedback

## Additional Features (Optional)

1. **Real-time Updates:**
   - WebSocket integration for live attendance tracking
   - Live attendee count on trainer dashboard

2. **Reports:**
   - Attendance summary reports
   - Risk score analytics
   - Export to CSV/PDF

3. **Notifications:**
   - Email notifications for check-ins
   - SMS alerts for high-risk scores

4. **Admin Dashboard:**
   - View all sessions
   - Manage sessions
   - View attendance analytics

## Documentation Requirements

1. **API Documentation:**
   - Endpoint descriptions
   - Request/response examples
   - Error codes

2. **User Guides:**
   - Trainer guide (how to create sessions, generate QR codes)
   - Trainee guide (how to check in)

3. **Developer Documentation:**
   - Architecture overview
   - Code structure
   - Extension points

## Implementation Checklist

- [ ] Database migrations created and run
- [ ] Models created with relationships
- [ ] QrTokenService implemented
- [ ] Controllers created with all methods
- [ ] Request validation classes created
- [ ] Middleware implemented
- [ ] Trainer dashboard view created
- [ ] QR code generation working
- [ ] Trainee check-in page created
- [ ] QR scanner integration working
- [ ] Geolocation API integrated
- [ ] Beacon system implemented
- [ ] Keyword challenge flow working
- [ ] Risk scoring implemented
- [ ] Event logging working
- [ ] Authentication configured
- [ ] Error handling implemented
- [ ] Tests written and passing
- [ ] Documentation completed

---

**Note:** This is a comprehensive implementation guide. Follow each phase sequentially, testing as you go. The system is designed to be modular, so you can implement and test each component independently.


