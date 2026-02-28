@echo off
echo =========================================
echo   Vaaga Academy File Organization
echo =========================================
echo.
echo This script organizes documentation files into _archive folder
echo.

REM Create directories
if not exist "_archive\reports" mkdir "_archive\reports"
if not exist "_archive\sql" mkdir "_archive\sql"
if not exist "_archive\assets" mkdir "_archive\assets"
if not exist "_archive\scripts" mkdir "_archive\scripts"

echo Moving Markdown files to _archive\reports\
move /Y "*.md" "_archive\reports\" >nul 2>&1

echo Moving SQL files to _archive\sql\
move /Y "*.sql" "_archive\sql\" >nul 2>&1

echo Moving image files to _archive\assets\
move /Y "*.png" "_archive\assets\" >nul 2>&1

echo Moving shell scripts to _archive\scripts\
move /Y "*.sh" "_archive\scripts\" >nul 2>&1

echo.
echo =========================================
echo   Organization Complete!
echo =========================================
echo.
echo Archive structure:
echo   _archive/
echo     ^|-- reports/  - Documentation and reports
echo     ^|-- sql/      - SQL scripts
echo     ^|-- assets/   - Images and screenshots
echo     ^+-- scripts/  - Shell scripts
echo.
pause
