# Complete Fix Commands for Laravel 10 Upgrade

## Current Issues:
1. Composer not in PATH (use `php composer.phar` instead)
2. Laravel 8 still installed (need to remove vendor and reinstall)
3. Package compatibility issues
4. Deprecation warnings from Laravel 8

## Step-by-Step Fix Commands:

### Step 1: Clear all caches
```bash
cd /c/Projects/vaagaacademy
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 2: Remove vendor directory
```bash
rm -rf vendor
```

### Step 3: Remove composer.lock (if exists)
```bash
rm -f composer.lock
```

### Step 4: Update composer.json packages (already done, but verify)
The following packages have been removed/updated:
- Removed: `hieu-le/active` (replaced with custom helper)
- Removed: `coderello/laravel-passport-social-grant` (incompatible - may need manual fix)
- Removed: `laravelium/sitemap` (incompatible - may need alternative)
- Removed: `torann/geoip` (PHP version conflict)
- Removed: `divineomega/laravel-password-exposed-validation-rule` (Guzzle conflict)
- Removed: `beyondcode/laravel-self-diagnosis` (PHP 8.2 required)
- Removed: `arcanedev/log-viewer` (Laravel 5.x only)

### Step 5: Install dependencies
```bash
php composer.phar install --no-interaction --prefer-dist
```

### Step 6: If Step 5 fails, try with update flag
```bash
php composer.phar update --with-all-dependencies --no-interaction --prefer-dist
```

### Step 7: Clear caches again
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Step 8: Verify Laravel version
```bash
php artisan --version
```
Should show Laravel 10.x

### Step 9: Check application status
```bash
php artisan about
```

### Step 10: Start development server
```bash
php artisan serve
```

## Manual Fixes Required:

### 1. Active Package Replacement
The `hieu-le/active` package has been replaced with a custom helper:
- File: `app/Helpers/Active.php`
- Facade alias updated in `config/app.php`
- Should work with existing views

### 2. Social Passport Grant (if needed)
If you use social login with Passport, you'll need to:
- Find a Laravel 10 compatible alternative for `coderello/laravel-passport-social-grant`
- Or manually implement social grant functionality

### 3. Sitemap Package (if needed)
If you use sitemap generation:
- Find Laravel 10 compatible sitemap package
- Or use Laravel's built-in sitemap functionality

### 4. Log Viewer (if needed)
If you use log viewer:
- Install Laravel 10 compatible log viewer package
- Or use Laravel's built-in log functionality

## Troubleshooting:

### If composer.phar doesn't work:
```bash
# Try using full path
php /c/Projects/vaagaacademy/composer.phar install
```

### If you get memory errors:
```bash
php -d memory_limit=2048M composer.phar install
```

### If specific packages still fail:
Check each package's Laravel 10 compatibility and either:
- Remove the package if not critical
- Find Laravel 10 compatible alternative
- Update to compatible version

### If you see deprecation warnings after Laravel 10 install:
These should be gone, but if they persist:
1. Check PHP version: `php -v` (should be 8.1+)
2. Clear all caches again
3. Check for any remaining Laravel 8 code

## After Successful Installation:

1. Test all major features:
   - User authentication
   - Course management
   - Payment processing
   - File uploads
   - API endpoints

2. Check error logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Run tests (if available):
   ```bash
   php artisan test
   ```

## Notes:
- Some packages may need to be removed temporarily and added back later
- Always backup your database before running migrations
- Test thoroughly after upgrade
