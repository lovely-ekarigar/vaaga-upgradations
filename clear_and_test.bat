@echo off
echo Clearing Laravel caches...
cd /d "C:\Projects\vaagaacademy"

php artisan cache:clear
echo ✓ Cache cleared

php artisan view:clear
echo ✓ View cache cleared

php artisan config:clear
echo ✓ Config cache cleared

echo.
echo Done! Now refresh your browser page.
echo Press any key to exit...
pause > nul
