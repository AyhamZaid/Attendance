# Fix: "Unknown database 'laravel'" Error

## The Problem
Even though your `.env` file has `DB_DATABASE=attendance`, Laravel is still trying to use the 'laravel' database.

## Solution Steps

### Step 1: Stop the Laravel Server
If your server is running, **STOP IT** (Press `Ctrl+C` in the terminal where it's running).

### Step 2: Verify .env File
Your `.env` file should have:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 4: Verify Database Exists
Check if the `attendance` database exists in MySQL:

**Option A: Using MySQL Command Line**
```bash
mysql -u root -p -e "SHOW DATABASES LIKE 'attendance';"
```

**Option B: Using phpMyAdmin**
- Open phpMyAdmin
- Check if `attendance` database exists in the left sidebar

**If the database doesn't exist, create it:**
```bash
mysql -u root -p -e "CREATE DATABASE attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Or using MySQL command line:
```bash
mysql -u root -p
```
Then run:
```sql
CREATE DATABASE attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Step 5: Restart the Server
```bash
php artisan serve
```

### Step 6: Try Login Again
Visit: `http://localhost:8000/login`

---

## Alternative: Use SQLite (Easier)

If MySQL is causing issues, switch to SQLite:

1. **Update .env:**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=C:\Users\rawan.abuseini\Desktop\attendance\database\database.sqlite
   ```

2. **Comment out MySQL settings:**
   ```env
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

3. **Create database file:**
   ```bash
   New-Item -ItemType File -Path "database\database.sqlite" -Force
   ```

4. **Clear cache:**
   ```bash
   php artisan config:clear
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate:fresh
   php artisan db:seed
   ```

6. **Restart server:**
   ```bash
   php artisan serve
   ```

---

## Why This Happens

Laravel caches configuration for performance. When you change `.env`, you need to:
1. Clear the config cache
2. Restart the server (if running)

The server process loads the config when it starts, so changes to `.env` won't take effect until you restart it.


