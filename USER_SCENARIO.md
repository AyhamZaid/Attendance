# User Scenario: Complete Attendance System Flow

This document describes the complete user journey through the attendance tracking system, from setup to check-in.

---

## Scenario Overview

**Setting:** A hybrid training session on "Introduction to Laravel"
**Participants:**
- **Trainer:** Sarah (trainer@example.com)
- **Trainee 1:** John (onsite)
- **Trainee 2:** Maria (remote)

---

## Part 1: System Setup (One-Time)

### Step 1: Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed
```

### Step 2: Start Server
```bash
php artisan serve
```

---

## Part 2: Trainer Flow

### Scenario: Sarah (Trainer) Prepares for Session

#### Step 1: Login
1. Sarah opens browser: `http://localhost:8000/login`
2. Enters credentials:
   - Email: `trainer@example.com`
   - Password: `password`
3. Clicks "Login"
4. ✅ **Result:** Redirected to home page, authenticated with `lms` guard

#### Step 2: Access Training Session
1. Sarah navigates to: `http://localhost:8000/trainer/sessions/1`
2. ✅ **Result:** Sees session page with:
   - Session title: "Hybrid Training Session - Introduction to Laravel"
   - Mode: Hybrid
   - Start/End times
   - **Two QR Code panels:**
     - Onsite QR Code (for physical attendees)
     - Remote QR Code (for remote attendees)
   - Keyword Challenge section
   - Logout button

#### Step 3: Display QR Codes
1. QR codes are automatically generated and displayed
2. Each QR code contains an encrypted token with:
   - Session ID
   - Mode (onsite/remote)
   - 45-second expiry timestamp
3. ✅ **Result:** QR codes visible on screen for trainees to scan

#### Step 4: Generate Keyword Challenge (Optional)
1. Sarah clicks "Generate Keyword" button
2. ✅ **Result:** A 6-character keyword appears (e.g., "ABC123")
3. Keyword is valid for 5 minutes
4. Sarah announces the keyword to trainees who can't scan QR codes

---

## Part 3: Trainee Flow - Onsite (John)

### Scenario: John Checks In Onsite

#### Step 1: Access Check-In Page
1. John opens browser on his phone: `http://localhost:8000/trainee/checkin`
2. ✅ **Result:** Sees check-in page with options:
   - "Scan QR Code" button
   - "Join Remote Session" button

#### Step 2: Scan QR Code
1. John clicks "Scan QR Code"
2. Browser requests camera permission → John grants it
3. Camera opens, showing live video feed
4. John points camera at the **Onsite QR Code** displayed by Sarah
5. ✅ **Result:** 
   - QR code is detected automatically
   - System extracts token from QR code
   - Geolocation is requested and captured
   - Check-in request is sent to server

#### Step 3: Check-In Processing
**Backend Process:**
1. Server validates QR token:
   - Decrypts token
   - Checks expiry (must be < 45 seconds old)
   - Verifies session ID and mode
2. Server creates attendance record:
   - Links to training session
   - Records mode: "onsite"
   - Stores geolocation (lat/lng)
   - Sets check-in timestamp
   - Calculates geo confidence score
3. ✅ **Result:** Check-in successful

#### Step 4: Presence Beacons
1. After check-in, system automatically starts sending beacons
2. Every 2 minutes, a beacon is sent with:
   - Current geolocation (if available)
   - Timestamp
3. ✅ **Result:** Continuous presence tracking throughout session

---

## Part 4: Trainee Flow - Remote (Maria)

### Scenario: Maria Checks In Remotely

#### Option A: Using QR Code (Remote)
1. Maria opens: `http://localhost:8000/trainee/checkin`
2. Clicks "Scan QR Code"
3. Points camera at the **Remote QR Code** from Sarah's screen
4. ✅ **Result:** Checked in as remote attendee

#### Option B: Using Keyword Challenge
1. Maria opens: `http://localhost:8000/trainee/checkin`
2. Sarah announces keyword: "ABC123"
3. Maria enters keyword in the keyword input field
4. Clicks "Submit Keyword"
5. ✅ **Result:** 
   - Server validates keyword (must match and be < 5 minutes old)
   - Check-in successful
   - Attendance record created with mode: "remote"

---

## Part 5: During the Session

### Continuous Monitoring

#### For All Trainees:
1. **Presence Beacons:** Sent every 2 minutes automatically
2. **Geolocation Updates:** Captured with each beacon (if available)
3. **Risk Scoring:** System calculates risk based on:
   - Attendance patterns
   - Geolocation consistency
   - Beacon frequency

#### For Trainer:
1. Can view attendance in real-time (if dashboard implemented)
2. Can generate new keywords as needed
3. QR codes auto-refresh every 45 seconds

---

## Part 6: Check-Out (Optional)

### Scenario: Trainee Leaves Early

1. Trainee closes browser tab or navigates away
2. Last beacon timestamp is recorded
3. System can calculate session duration
4. ✅ **Result:** Complete attendance record with:
   - Check-in time
   - Check-out time (if applicable)
   - Total session duration
   - All beacon timestamps
   - Geolocation history

---

## Complete Data Flow Diagram

```
┌─────────────┐
│   Trainer   │
│   Logs In   │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│  Views Session Page │
│  QR Codes Generated │
└──────┬──────────────┘
       │
       ├──────────────────┐
       │                  │
       ▼                  ▼
┌─────────────┐    ┌─────────────┐
│ Onsite QR   │    │ Remote QR   │
│ Code        │    │ Code        │
└──────┬──────┘    └──────┬──────┘
       │                  │
       │                  │
       ▼                  ▼
┌─────────────┐    ┌─────────────┐
│  Trainee    │    │  Trainee    │
│  (Onsite)   │    │  (Remote)   │
│  Scans QR   │    │  Scans QR   │
└──────┬──────┘    └──────┬──────┘
       │                  │
       ├──────────────────┘
       │
       ▼
┌─────────────────────┐
│  Check-In Request   │
│  - Token Validation │
│  - Geo Capture      │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│  Attendance Record  │
│  Created            │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│  Beacon System      │
│  Starts (Every 2min)│
└─────────────────────┘
```

---

## User Stories

### As a Trainer, I want to:
- ✅ Log in securely with my credentials
- ✅ View my training sessions
- ✅ Generate QR codes for onsite and remote attendees
- ✅ Create keyword challenges for alternative check-in
- ✅ See who has checked in (if dashboard implemented)
- ✅ Log out when done

### As a Trainee, I want to:
- ✅ Check in quickly using QR code scanning
- ✅ Check in remotely if I'm not physically present
- ✅ Use keyword challenge if QR scanning fails
- ✅ Have my presence tracked automatically
- ✅ Not worry about manually checking in repeatedly

---

## Technical Details

### QR Code Token Structure
```json
{
  "session_id": 1,
  "mode": "onsite",
  "expires_at": 1234567890
}
```
- Encrypted using Laravel's Crypt
- Valid for 45 seconds
- Auto-refreshes on trainer page

### Attendance Record
```php
{
  "training_session_id": 1,
  "lms_user_id": "uuid-here",
  "mode": "onsite",
  "checked_in_at": "2024-01-15 10:00:00",
  "last_beacon_at": "2024-01-15 10:02:00",
  "geo_confidence": 0.9,
  "risk_score": 0
}
```

### Beacon Payload
```json
{
  "lat": 40.7128,
  "lng": -74.0060,
  "timestamp": "2024-01-15T10:02:00Z"
}
```

---

## Testing the Complete Scenario

### Quick Test Steps:

1. **Start Server:**
   ```bash
   php artisan serve
   ```

2. **Login as Trainer:**
   - Go to: `http://localhost:8000/login`
   - Email: `trainer@example.com`
   - Password: `password`

3. **View Session:**
   - Go to: `http://localhost:8000/trainer/sessions/1`
   - See QR codes displayed

4. **Check-In as Trainee:**
   - Open new browser/incognito: `http://localhost:8000/trainee/checkin`
   - Click "Scan QR Code"
   - Point camera at QR code from trainer page
   - ✅ Check-in successful!

5. **Verify in Database:**
   ```bash
   php artisan tinker
   ```
   ```php
   App\Models\Attendance::all();
   ```

---

## Common Scenarios

### Scenario 1: QR Code Expired
- **Problem:** Trainee scans QR code after 45 seconds
- **Solution:** QR code auto-refreshes on trainer page, trainee scans new code

### Scenario 2: Camera Permission Denied
- **Problem:** Browser blocks camera access
- **Solution:** Use keyword challenge instead

### Scenario 3: Poor Internet Connection
- **Problem:** Beacon fails to send
- **Solution:** System logs error, next beacon will retry

### Scenario 4: Multiple Devices
- **Problem:** Trainee wants to check in from phone but use laptop
- **Solution:** Check-in from phone, beacons continue from any device with session

---

## Success Criteria

✅ **Trainer can:**
- Generate and display QR codes
- Create keyword challenges
- View session details

✅ **Trainee can:**
- Check in via QR code
- Check in via keyword
- Have presence tracked automatically

✅ **System:**
- Validates tokens securely
- Tracks geolocation
- Sends beacons automatically
- Records complete attendance history

---

This scenario demonstrates the complete user journey through the attendance system!


