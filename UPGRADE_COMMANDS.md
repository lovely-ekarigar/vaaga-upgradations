# Laravel Upgrade Commands

## Step 1: Test Current Application Status

```bash
# Check Laravel version
php artisan --version

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Check if application boots without errors
php artisan about
```

## Step 2: Test Application Server

```bash
# Start development server (will show if there are any errors)
php artisan serve

# Or start on specific host/port
php artisan serve --host=127.0.0.1 --port=8000
```

## Step 3: Check for Errors

```bash
# View recent logs
tail -f storage/logs/laravel.log

# Or on Windows Git Bash:
tail -f storage/logs/laravel.log

# Check for specific errors
grep -i "error" storage/logs/laravel.log | tail -20
```

## Step 4: Verify Routes Work

```bash
# List all routes
php artisan route:list

# Check specific route
php artisan route:list --name=home.index
```

## Step 5: Test Database Connection

```bash
# Test database connection
php artisan migrate:status

# Or just check connection
php artisan tinker
# Then in tinker: DB::connection()->getPdo();
```

## Step 6: Update Dependencies (When Ready)

```bash
# First, try updating Laravel framework only
php vendor/composer/composer/bin/composer update laravel/framework --with-dependencies --no-interaction

# If that works, update all dependencies
php vendor/composer/composer/bin/composer update --no-interaction --prefer-dist

# Or use composer directly if available
composer update --no-interaction --prefer-dist
```

## Step 7: After Successful Update

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild autoloader
composer dump-autoload

# Run migrations (if needed)
php artisan migrate

# Check version again
php artisan --version
```

## Step 8: Remove Deprecation Suppression (After Laravel 10 Upgrade)

Once Laravel 10 is installed, remove these lines from:
- `artisan` (lines with error_reporting)
- `public/index.php` (lines with error_reporting)

## Quick Health Check Commands

```bash
# All-in-one health check
php artisan config:clear && \
php artisan cache:clear && \
php artisan route:clear && \
php artisan view:clear && \
php artisan --version && \
echo "✓ Caches cleared, checking routes..." && \
php artisan route:list | head -10
```

## Troubleshooting Commands

```bash
# Check PHP version
php -v

# Check Composer version
php vendor/composer/composer/bin/composer --version

# Check installed Laravel version
php -r "require 'vendor/autoload.php'; echo \Illuminate\Foundation\Application::VERSION;"

# Check for syntax errors in routes
php artisan route:list 2>&1 | grep -i error

# Check environment
php artisan env
```
