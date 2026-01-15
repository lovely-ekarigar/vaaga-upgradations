# Database Setup Instructions

## Port Configuration Fixed ✅

Your `.env` file has been updated:
- `DB_PORT=8889` (MAMP PRO MySQL port)
- `DB2_PORT=8889` (MAMP PRO MySQL port)

## Current Issue: Database User Authentication

The database user `u372758074_elearn` doesn't exist in your local MAMP MySQL instance.

## Solution Options:

### Option 1: Create the Database User in MAMP MySQL (Recommended)

1. Open **MAMP PRO**
2. Go to **Tools** → **phpMyAdmin** (or access phpMyAdmin at `http://localhost:8888/phpMyAdmin`)
3. Click on **SQL** tab
4. Run these SQL commands:

```sql
CREATE DATABASE IF NOT EXISTS `u372758074_elearn`;
CREATE USER IF NOT EXISTS 'u372758074_elearn'@'localhost' IDENTIFIED BY '!Have99$..';
GRANT ALL PRIVILEGES ON `u372758074_elearn`.* TO 'u372758074_elearn'@'localhost';
FLUSH PRIVILEGES;
```

5. Also create the second database:

```sql
CREATE DATABASE IF NOT EXISTS `u372758074_exam`;
CREATE USER IF NOT EXISTS 'u372758074_exam'@'localhost' IDENTIFIED BY '+2iuqlLyWW3v';
GRANT ALL PRIVILEGES ON `u372758074_exam`.* TO 'u372758074_exam'@'localhost';
FLUSH PRIVILEGES;
```

### Option 2: Use MAMP Default MySQL Credentials (Temporary)

If you want to test quickly, temporarily update `.env`:

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=8889
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=root
```

Then create your databases and import your schema.

## After Database Setup:

1. Clear Laravel config cache:
   ```bash
   php artisan config:clear
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

3. Restart the server:
   ```bash
   php artisan serve
   ```

## Current Status:

- ✅ Port configuration fixed (8889)
- ✅ Session driver set to file (no database required for sessions)
- ⚠️ Database user needs to be created in MAMP MySQL
- ✅ Application error handling improved
