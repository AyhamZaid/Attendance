# Testing QR Code Generation - Step by Step Guide

## Prerequisites Check

Before testing, ensure your environment is set up:

### 1. Verify Database Setup
```bash
# Check if database exists and migrations are run
php artisan migrate:status
```

If tables don't exist, run:
```bash
php artisan migrate
```

### 2. Seed Test Data
```bash
# Create a sample training session
php artisan db:seed --class=TrainingSessionSeeder
```

### 3. Create a Test User (if needed)
```bash
# Option 1: Use the test login route (easiest)
# Just visit: http://localhost:8000/test-login

# Option 2: Create user manually via Tinker
php artisan tinker
```
Then in Tinker:
```php
App\User::create([
    'name' => 'Test Trainer',
    'email' => 'trainer@test.com',
    'password' => bcrypt('password')
]);
exit
```

### 4. Start the Development Server
```bash
php artisan serve
```

The server will start at: **http://localhost:8000**

---

## Testing Steps

### Step 1: Login as Trainer

**Option A: Quick Test Login (Recommended)**
1. Open your browser
2. Navigate to: `http://localhost:8000/test-login`
3. You should be automatically logged in and redirected

**Option B: Manual Login**
1. Navigate to: `http://localhost:8000/login`
2. Enter your credentials (email and password)
3. Click "Login"

### Step 2: Access Training Session Page

**Option A: Direct Access**
1. Navigate to: `http://localhost:8000/trainer/sessions`
   - This will automatically redirect to the first available session

**Option B: Direct Session ID**
1. Find the session ID (usually `1` if you just seeded)
2. Navigate to: `http://localhost:8000/trainer/sessions/1`

### Step 3: Verify QR Code Display

On the session page, you should see:

1. **Session Information**
   - Title: "Hybrid Training Session - Introduction to Laravel"
   - Mode: Hybrid (or Onsite/Remote depending on session)
   - Start and End times

2. **QR Code Sections**
   - **For Hybrid sessions**: You should see TWO QR code panels:
     - "Onsite QR Code" (left side)
     - "Remote QR Code" (right side)
   - **For Onsite sessions**: One "Onsite QR Code" panel
   - **For Remote sessions**: One "Remote QR Code" panel

3. **What to Look For:**
   - ✅ QR codes should appear as black and white square patterns
   - ✅ Each QR code should be approximately 300x300 pixels
   - ✅ Below each QR code, you should see "Mode: Onsite" or "Mode: Remote"

### Step 4: Check Browser Console

1. **Open Developer Tools**
   - Press `F12` (Windows/Linux) or `Cmd+Option+I` (Mac)
   - Or right-click → "Inspect" → "Console" tab

2. **Look for Success Messages:**
   ```
   QRCode library loaded from: https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js
   Generating QR code for mode: onsite
   QR Code generated successfully for mode: onsite
   Generating QR code for mode: remote
   QR Code generated successfully for mode: remote
   ```

3. **If You See Errors:**
   - `QRCode library not available` → CDN loading issue
   - `Token is missing` → Backend token generation issue
   - `Error generating QR code: [message]` → QR code generation issue

### Step 5: Test QR Code Scanning (Optional)

To verify the QR codes are scannable:

1. **Using a Mobile Device:**
   - Open your phone's camera app
   - Point it at the QR code on your screen
   - The camera should recognize it as a QR code

2. **Using a QR Scanner App:**
   - Install a QR scanner app (e.g., "QR Code Reader")
   - Scan the QR code
   - It should display the encrypted token (long string of characters)

### Step 6: Test QR Code Refresh

QR codes expire after 45 seconds. To test:

1. Wait 45+ seconds
2. Refresh the page (F5)
3. New QR codes should be generated with different tokens
4. Old QR codes should no longer be valid

---

## Troubleshooting

### Issue: "QR Code library failed to load"

**Solutions:**
1. **Check Internet Connection**
   - The library loads from CDN, so you need internet access
   - Try refreshing the page

2. **Check Browser Console**
   - Look for network errors (404, CORS, etc.)
   - The script tries 3 different CDNs automatically

3. **Try Hard Refresh**
   - Windows/Linux: `Ctrl + F5`
   - Mac: `Cmd + Shift + R`

4. **Check Firewall/Proxy**
   - Some corporate networks block CDN access
   - Try from a different network

### Issue: "Token is missing"

**Solutions:**
1. **Check Backend Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verify Session Exists**
   ```bash
   php artisan tinker
   App\Models\TrainingSession::first()
   ```

3. **Check Controller**
   - Ensure `TrainerSessionController@show` is working
   - Verify `QrTokenService` is being injected

### Issue: QR Codes Not Visible

**Solutions:**
1. **Check CSS**
   - Inspect element to see if canvas is created
   - Check if container has proper dimensions

2. **Check JavaScript Errors**
   - Open console (F12)
   - Look for red error messages

3. **Verify Data Attributes**
   - Inspect the `.qr-code-container` element
   - Check if `data-token` and `data-mode` attributes exist

### Issue: Only One QR Code Shows (Hybrid Session)

**Expected Behavior:**
- Hybrid sessions should show TWO QR codes (onsite + remote)
- If only one shows, check:
  1. Session mode in database
  2. Controller logic in `TrainerSessionController@show`

---

## Quick Verification Checklist

- [ ] Server is running (`php artisan serve`)
- [ ] Database is migrated (`php artisan migrate`)
- [ ] Test session exists (`php artisan db:seed --class=TrainingSessionSeeder`)
- [ ] User is logged in (visit `/test-login`)
- [ ] Can access session page (`/trainer/sessions/1`)
- [ ] QR codes are visible on the page
- [ ] Browser console shows success messages
- [ ] QR codes are scannable (optional)

---

## Expected Results

✅ **Success Indicators:**
- QR codes display as black/white square patterns
- Console shows "QR Code generated successfully"
- No red error messages in console
- QR codes are scannable with a phone camera

❌ **Failure Indicators:**
- Red error messages in console
- "QR Code library failed to load" message
- Empty white boxes instead of QR codes
- "Token is missing" error

---

## Additional Test Scenarios

### Test Different Session Modes

1. **Create Onsite Session:**
   ```bash
   php artisan tinker
   ```
   ```php
   App\Models\TrainingSession::create([
       'lms_session_id' => (string) \Illuminate\Support\Str::uuid(),
       'title' => 'Onsite Only Session',
       'mode' => 'onsite',
       'lat' => 40.7128,
       'lng' => -74.0060,
       'geo_radius_m' => 100,
       'starts_at' => \Carbon\Carbon::now()->addDays(1)->setTime(10, 0),
       'ends_at' => \Carbon\Carbon::now()->addDays(1)->setTime(12, 0),
   ]);
   exit
   ```

2. **Create Remote Session:**
   ```php
   App\Models\TrainingSession::create([
       'lms_session_id' => (string) \Illuminate\Support\Str::uuid(),
       'title' => 'Remote Only Session',
       'mode' => 'remote',
       'starts_at' => \Carbon\Carbon::now()->addDays(1)->setTime(10, 0),
       'ends_at' => \Carbon\Carbon::now()->addDays(1)->setTime(12, 0),
   ]);
   ```

3. **Verify:**
   - Onsite session → 1 QR code (onsite)
   - Remote session → 1 QR code (remote)
   - Hybrid session → 2 QR codes (onsite + remote)

---

## Need Help?

If QR codes still don't appear:

1. **Share Browser Console Output:**
   - Copy all messages from the Console tab
   - Include any red error messages

2. **Check Network Tab:**
   - Open Network tab in DevTools
   - Look for failed requests (red status codes)
   - Check if `qrcode.min.js` is loading

3. **Verify Backend:**
   ```bash
   # Check if tokens are being generated
   php artisan tinker
   ```
   ```php
   $session = App\Models\TrainingSession::first();
   $service = app(App\Services\QrTokenService::class);
   $token = $service->generate(['session_id' => $session->id, 'mode' => 'onsite']);
   echo $token; // Should output encrypted token
   ```


