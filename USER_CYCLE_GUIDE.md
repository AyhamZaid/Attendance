# Complete User Authentication Cycle Guide

This guide walks you through the complete user authentication cycle: Registration → Login → Using the System → Logout.

## Step 1: Setup Database and Seed Test Users

### 1.1 Run Migrations
```bash
php artisan migrate
```

### 1.2 Seed Test Users and Training Sessions
```bash
php artisan db:seed
```

This will create:
- **Trainer User**: `trainer@example.com` / `password`
- **Trainee User**: `trainee@example.com` / `password`
- **Sample Training Session** (Hybrid mode)

---

## Step 2: Start the Development Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

---

## Step 3: Complete User Cycle

### 3.1 Registration (Create New User)

1. **Visit Registration Page**:
   ```
   http://localhost:8000/register
   ```

2. **Fill in the Registration Form**:
   - Name: Your full name
   - Email: Your email address
   - Password: Minimum 8 characters
   - Confirm Password: Re-enter your password

3. **Submit the Form**:
   - You will be automatically logged in after registration
   - You'll be redirected to the home page

### 3.2 Login (Existing Users)

1. **Visit Login Page**:
   ```
   http://localhost:8000/login
   ```

2. **Enter Credentials**:
   - Email: `trainer@example.com` (or your registered email)
   - Password: `password` (or your password)
   - Optional: Check "Remember me" to stay logged in

3. **Click Login**:
   - You'll be authenticated with the `lms` guard
   - You'll be redirected to the home page

### 3.3 Using the System (After Login)

#### As a Trainer:

1. **View Training Session with QR Codes**:
   ```
   http://localhost:8000/trainer/sessions/1
   ```
   (Replace `1` with your session ID)

2. **What You'll See**:
   - Session title and details
   - QR codes for onsite and/or remote check-in (depending on session mode)
   - Keyword challenge generator
   - Logout button

3. **Generate Keyword Challenge**:
   - Click "Generate Keyword" button
   - A 6-character keyword will appear
   - This keyword is valid for 5 minutes

#### As a Trainee:

1. **Check-In Page**:
   ```
   http://localhost:8000/trainee/checkin
   ```
   (This page doesn't require authentication)

2. **Check-In Methods**:
   - **Scan QR Code**: Click "Scan QR Code" button
     - Grant camera permissions
     - Point camera at the QR code displayed by trainer
     - System will automatically check you in
   
   - **Join Remote**: Click "Join Remote Session" button
     - Enter session ID when prompted
   
   - **Keyword Challenge**: Enter the 6-character keyword provided by trainer

### 3.4 Logout

1. **From Trainer Session Page**:
   - Click the "Logout" button in the top right corner

2. **Or Manually**:
   - Send a POST request to `/logout`
   - You'll be redirected to the login page

---

## Step 4: Quick Test Login (Development Only)

For quick testing without going through the full login process:

```
http://localhost:8000/test-login
```

This automatically logs you in as the first user in the database.

**⚠️ Warning**: Remove this route in production!

---

## Complete User Flow Diagram

```
┌─────────────────┐
│   Registration  │
│   /register     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Auto-Logged In │
└────────┬────────┘
         │
         ▼
┌─────────────────┐      ┌──────────────────┐
│   Login Page    │◄─────│   Logout Action  │
│   /login        │      │   /logout        │
└────────┬────────┘      └──────────────────┘
         │
         ▼
┌─────────────────┐
│  Authenticated  │
│  (lms guard)    │
└────────┬────────┘
         │
         ├─────────────────┬──────────────────┐
         ▼                 ▼                  ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   Trainer    │  │   Trainee    │  │  Home Page   │
│   Sessions   │  │   Check-In   │  │      /       │
│   /trainer/  │  │  /trainee/   │  │              │
└──────────────┘  └──────────────┘  └──────────────┘
```

---

## Available Routes

| Route | Method | Description | Auth Required |
|-------|--------|-------------|---------------|
| `/` | GET | Home page | No |
| `/register` | GET | Registration form | No |
| `/register` | POST | Create new user | No |
| `/login` | GET | Login form | No |
| `/login` | POST | Authenticate user | No |
| `/logout` | POST | Logout user | Yes (lms) |
| `/test-login` | GET | Auto-login (dev only) | No |
| `/trainer/sessions/{id}` | GET | Trainer session view | Yes (lms) |
| `/trainee/checkin` | GET | Trainee check-in page | No |

---

## Test Credentials

After running `php artisan db:seed`, you can use:

**Trainer Account:**
- Email: `trainer@example.com`
- Password: `password`

**Trainee Account:**
- Email: `trainee@example.com`
- Password: `password`

---

## Troubleshooting

### "No users found" error
```bash
php artisan db:seed --class=UserSeeder
```

### "Route [login] not defined" error
```bash
php artisan route:clear
php artisan config:clear
```

### Can't access trainer sessions
- Make sure you're logged in with the `lms` guard
- Visit `/login` first, then try accessing trainer routes

### Session not found
```bash
php artisan db:seed --class=TrainingSessionSeeder
```

---

## Next Steps

1. ✅ Registration - Create new users
2. ✅ Login - Authenticate existing users
3. ✅ View QR Codes - Access trainer session page
4. ✅ Generate Keywords - Create challenge keywords
5. ✅ Check-In - Use trainee check-in page
6. ✅ Logout - End session

You now have a complete user authentication cycle set up!


