# Database Setup Guide

## Quick Fix for Database Error

The error "Database name seems incorrect - You're using the default database name laravel" means your `.env` file needs to be configured with the correct database settings.

## Step 1: Create the Database

### For MySQL:

1. **Open MySQL Command Line or phpMyAdmin**

2. **Create the database:**
   ```sql
   CREATE DATABASE attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

   Or using command line:
   ```bash
   mysql -u root -p -e "CREATE DATABASE attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

### For SQLite (Easier for Testing):

No need to create a database manually - Laravel will create the file automatically.

## Step 2: Configure .env File

Open your `.env` file in the project root and update the database section:

### For MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

**Replace:**
- `DB_DATABASE=attendance` - Use the name of the database you created
- `DB_USERNAME=root` - Your MySQL username
- `DB_PASSWORD=your_mysql_password` - Your MySQL password (leave empty if no password)

### For SQLite (Recommended for Quick Testing):

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\Users\rawan.abuseini\Desktop\attendance\database\database.sqlite
```

Then create the database file:
```bash
New-Item -ItemType File -Path "database\database.sqlite" -Force
```

## Step 3: Verify Configuration

After updating `.env`, clear the config cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 4: Test Database Connection

```bash
php artisan migrate:status
```

If this works without errors, your database is configured correctly!

## Step 5: Run Migrations

```bash
php artisan migrate
```

## Step 6: Seed the Database

```bash
php artisan db:seed
```

This creates:
- Test users (trainer@example.com and trainee@example.com)
- Sample training session

---

## Common Issues

### Issue: "Access denied for user"
**Solution:** Check your `DB_USERNAME` and `DB_PASSWORD` in `.env`

### Issue: "Unknown database"
**Solution:** Make sure you created the database first (see Step 1)

### Issue: "SQLSTATE[HY000] [2002] No connection could be made"
**Solution:** 
- Check if MySQL is running
- Verify `DB_HOST` is correct (usually `127.0.0.1` or `localhost`)

### Issue: Still showing "laravel" database
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
```
Then restart your server if it's running.

---

## Quick SQLite Setup (Easiest for Testing)

If you want to quickly test without setting up MySQL:

1. **Update .env:**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=C:\Users\rawan.abuseini\Desktop\attendance\database\database.sqlite
   ```

2. **Create database file:**
   ```bash
   New-Item -ItemType File -Path "database\database.sqlite" -Force
   ```

3. **Clear cache:**
   ```bash
   php artisan config:clear
   ```

4. **Run migrations:**
   ```bash
   php artisan migrate
   ```

5. **Seed data:**
   ```bash
   php artisan db:seed
   ```

Done! Your database is ready.


