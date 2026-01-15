# Laravel 10 Upgrade - Fix Commands

## Step-by-Step Fix Commands

### Step 1: Clear all caches and remove old vendor files
```bash
cd /c/Projects/vaagaacademy
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 2: Remove vendor directory (we'll reinstall with Laravel 10)
```bash
rm -rf vendor
```

### Step 3: Remove composer.lock if it exists (we'll regenerate it)
```bash
rm -f composer.lock
```

### Step 4: Install Laravel 10 and all dependencies
```bash
php composer.phar install --no-interaction --prefer-dist
```

### Step 5: If Step 4 fails, try with update flag
```bash
php composer.phar update --with-all-dependencies --no-interaction
```

### Step 6: Clear all caches again after installation
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Step 7: Verify Laravel version
```bash
php artisan --version
```

### Step 8: Check for any errors
```bash
php artisan about
```

### Step 9: If you see deprecation warnings, they should be gone after Laravel 10 installs
If warnings persist, check PHP version:
```bash
php -v
```
Should be PHP 8.1 or higher.

### Step 10: Start the development server
```bash
php artisan serve
```

## Alternative: If composer.phar doesn't work

### Option A: Install Composer globally
```bash
# Download composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Then use:
php composer.phar install
```

### Option B: Use full path to composer
If composer is installed elsewhere:
```bash
# Find composer
find /c -name composer.phar 2>/dev/null | head -1

# Then use full path:
php /path/to/composer.phar install
```

## Troubleshooting

### If you get memory errors:
```bash
php -d memory_limit=2048M composer.phar install
```

### If you get dependency conflicts:
```bash
php composer.phar update --with-all-dependencies --no-interaction --prefer-dist
```

### If specific packages fail:
Check package compatibility and update composer.json if needed.
