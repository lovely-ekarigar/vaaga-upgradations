@echo off
setlocal EnableDelayedExpansion

echo ==========================================
echo File Organization Script for Vaaga Academy
echo ==========================================
echo.

set "BASE_DIR=c:\Projects\vaagaacademy"

:: Create directories
echo Creating directory structure...
mkdir "%BASE_DIR%\_archive\reports" 2>nul
mkdir "%BASE_DIR%\_archive\sql" 2>nul
mkdir "%BASE_DIR%\_archive\assets" 2>nul
mkdir "%BASE_DIR%\_archive\scripts" 2>nul
echo Directories created.
echo.

set /a REPORTS=0
set /a SQL=0
set /a ASSETS=0
set /a SCRIPTS=0

:: Move Markdown files and text references to reports
echo Moving reports and documentation files...
call :moveFile "1ON1_PRICING_IMPLEMENTATION_SUMMARY.md" "reports"
call :moveFile "ADMIN_DASHBOARD_AUDIT_REPORT.md" "reports"
call :moveFile "AGENTS.md" "reports"
call :moveFile "app_comparison_final_report.md" "reports"
call :moveFile "app_comparison_summary.txt" "reports"
call :moveFile "APPLY_ALL_FIXES_NOW.md" "reports"
call :moveFile "APPLY_FIX_NOW.md" "reports"
call :moveFile "ARCHITECTURAL_RECONCILIATION_REPORT.md" "reports"
call :moveFile "BEST_SOLUTION.md" "reports"
call :moveFile "CKEditor_AND_DB_FIX.md" "reports"
call :moveFile "CKEDITOR_IMPLEMENTATION_COMPLETE.md" "reports"
call :moveFile "CKEDITOR_QUESTIONS_FIX.md" "reports"
call :moveFile "CLEANUP_TEMP_FILES.md" "reports"
call :moveFile "COMPARE_AND_EXPORT.md" "reports"
call :moveFile "COMPLETE_FIXES_SUMMARY.md" "reports"
call :moveFile "COMPLETE_FIX_GUIDE.md" "reports"
call :moveFile "COMPLETE_SOLUTION.md" "reports"
call :moveFile "COMPREHENSIVE_END_TO_END_AUDIT_REPORT.md" "reports"
call :moveFile "COMPREHENSIVE_FEATURE_PARITY_AUDIT_FINAL.md" "reports"
call :moveFile "COMPREHENSIVE_ROUTE_CONTROLLER_AUDIT_REPORT.md" "reports"
call :moveFile "CONFLICT_RESOLUTION_REPORT.md" "reports"
call :moveFile "contact_page_detailed.md" "reports"
call :moveFile "COPY_IMAGES_INSTRUCTIONS.md" "reports"
call :moveFile "CORE_LMS_FEATURES_AUDIT_REPORT.md" "reports"
call :moveFile "COURSE_MODE_CHANGE_FEATURE.md" "reports"
call :moveFile "CRITICAL_FIXES_NEEDED.md" "reports"
call :moveFile "CRITICAL_FIXES_SCRIPT.md" "reports"
call :moveFile "DASHBOARD_FIXES_APPLIED.md" "reports"
call :moveFile "DASHBOARD_FIXES_REPORT.md" "reports"
call :moveFile "DATABASE_ANALYSIS_REPORT.md" "reports"
call :moveFile "DATABASE_COMPARISON_REPORT.md" "reports"
call :moveFile "DATA_DISPLAY_FIXES.md" "reports"
call :moveFile "DEBUG_COD_ORDER.md" "reports"
call :moveFile "DEEP_COMPARISON_REPORT.md" "reports"
call :moveFile "DEPLOYMENT_GUIDE.md" "reports"
call :moveFile "DEPLOYMENT_HOSTINGER.md" "reports"
call :moveFile "DEPLOY_INSTRUCTIONS.md" "reports"
call :moveFile "DEPLOY_TO_PRODUCTION.md" "reports"
call :moveFile "DETAILED_ROUTE_RECONCILIATION_AUDIT_REPORT.md" "reports"
call :moveFile "EDGE_CASES_BUSINESS_LOGIC_AUDIT_REPORT.md" "reports"
call :moveFile "EDGE_CASES_QUICK_REFERENCE.md" "reports"
call :moveFile "ELOQUENT_MODELS_AUDIT_REPORT.md" "reports"
call :moveFile "EMI_DASHBOARD_IMPLEMENTATION_SUMMARY.md" "reports"
call :moveFile "EMI_DASHBOARD_SETUP.md" "reports"
call :moveFile "EMI_DISPLAY_UPDATE_SUMMARY.md" "reports"
call :moveFile "EMI_DUE_DATE_CALCULATION_FIX.md" "reports"
call :moveFile "EMI_PAYMENT_TRACKING_SYSTEM.md" "reports"
call :moveFile "EMI_REMINDER_SYSTEM.md" "reports"
call :moveFile "EMI_TRANSPARENT_TRACKING_IMPLEMENTATION.md" "reports"
call :moveFile "EXPECTED_TABLES_LIST.md" "reports"
call :moveFile "FEATURE_RESTORATION_SUMMARY.md" "reports"
call :moveFile "FINAL_CONFIG_AND_APP_COMPARISON.md" "reports"
call :moveFile "FINAL_DELIVERY_SUMMARY.md" "reports"
call :moveFile "FINAL_FIXES_APPLIED.md" "reports"
call :moveFile "FINAL_FIX_HINDI.md" "reports"
call :moveFile "FINAL_FIX_INSTRUCTIONS.md" "reports"
call :moveFile "FINAL_FIX_STEPS.md" "reports"
call :moveFile "FINAL_MODEL_COMPARISON_SUMMARY.md" "reports"
call :moveFile "FINAL_SOLUTION.md" "reports"
call :moveFile "FINAL_SOLUTION_HINDI.md" "reports"
call :moveFile "FINAL_TESTING_CHECKLIST.md" "reports"
call :moveFile "FIXES_APPLIED.md" "reports"
call :moveFile "FIXES_IMPLEMENTATION_SUMMARY.md" "reports"
call :moveFile "FIX_INSTRUCTIONS.md" "reports"
call :moveFile "FIX_PAGE_EXPIRED.md" "reports"
call :moveFile "FIX_PRODUCTION.md" "reports"
call :moveFile "FIX_ROLE_ERROR.md" "reports"
call :moveFile "FIX_TEST_SERIES_ORDER_ID_COLUMN.md" "reports"
call :moveFile "FRONTEND_AUDIT_REPORT.md" "reports"
call :moveFile "FRONTEND_AUDIT_SUMMARY.md" "reports"
call :moveFile "FRONTEND_VIEWS_AUDIT_REPORT.md" "reports"
call :moveFile "GST_CALCULATION_LOGIC.md" "reports"
call :moveFile "GST_REPORTS_FIXES_SUMMARY.md" "reports"
call :moveFile "HOW_TO_FIX.md" "reports"
call :moveFile "IMPLEMENTATION_COMPLETE.md" "reports"
call :moveFile "IMPLEMENTATION_SUMMARY.md" "reports"
call :moveFile "INVOICE_FIX_CHECKLIST.md" "reports"
call :moveFile "INVOICE_FIX_SUMMARY.md" "reports"
call :moveFile "INVOICE_GENERATION_FIX_COMPLETE.md" "reports"
call :moveFile "ISSUES_FIX_COMPLETE_SUMMARY.md" "reports"
call :moveFile "LARAVEL_CONFIG_AND_CRITICAL_FILES_AUDIT_REPORT.md" "reports"
call :moveFile "MISSING_TABLES_SUMMARY.md" "reports"
call :moveFile "MOCKTEST_VIEWS_AUDIT_REPORT.md" "reports"
call :moveFile "MOCK_TABLES_MISSING_FIX.md" "reports"
call :moveFile "MOCK_TESTS_FIX_SUMMARY.md" "reports"
call :moveFile "MOCK_TEST_MIGRATION_COMPLETE_GUIDE.md" "reports"
call :moveFile "MOCK_TEST_SERIES_CONTROLLER_AUDIT_REPORT.md" "reports"
call :moveFile "MOCK_TEST_SERIES_MIGRATION_AUDIT_REPORT.md" "reports"
call :moveFile "MOCK_TEST_SERIES_MODELS_AUDIT_REPORT.md" "reports"
call :moveFile "MOCK_TEST_SERIES_ROUTE_AUDIT_REPORT.md" "reports"
call :moveFile "MOCK_TEST_TUTOR_IMPLEMENTATION_GUIDE.md" "reports"
call :moveFile "MODEL_COMPARISON_REPORT.md" "reports"
call :moveFile "NAVIGATION_PAGES_FIX_REPORT.md" "reports"
call :moveFile "PAYMENT_IMPROVEMENTS_IMPLEMENTATION_SUMMARY.md" "reports"
call :moveFile "PAYMENT_LINK_IMPLEMENTATION_SUMMARY.md" "reports"
call :moveFile "PAYMENT_ORDER_AUDIT_REPORT.md" "reports"
call :moveFile "PAYMENT_ORDER_REFACTOR_COMPLETE_SUMMARY.md" "reports"
call :moveFile "PERMANENT_FIX_CSRF.md" "reports"
call :moveFile "PRICING_CAPTURE_FIX_SUMMARY.md" "reports"
call :moveFile "PRODUCTION_DEPLOYMENT_CHECKLIST.md" "reports"
call :moveFile "PROJECT_AUDIT_SUMMARY.md" "reports"
call :moveFile "PROJECT_COMPARISON_REPORT.md" "reports"
call :moveFile "PROJECT_FIXES_SUMMARY.md" "reports"
call :moveFile "QUESTION_BANK_UPGRADE_SUMMARY.md" "reports"
call :moveFile "QUICK_CHECK_COMMANDS.md" "reports"
call :moveFile "README.md" "reports"
call :moveFile "README_SOURCE.md" "reports"
call :moveFile "resources_comparison_report.md" "reports"
call :moveFile "ROUTES_API_AUDIT_REPORT.md" "reports"
call :moveFile "ROUTE_FIXES_APPLIED.md" "reports"
call :moveFile "SETUP_LOCAL_ENVIRONMENT.md" "reports"
call :moveFile "SHADOW_FIELDS_ANALYSIS.md" "reports"
call :moveFile "SIDEBAR_MENU_FIX_SUMMARY.md" "reports"
call :moveFile "SKILL_PAYMENT_IMPROVEMENTS.md" "reports"
call :moveFile "SOFTDELETES_REMOVED.md" "reports"
call :moveFile "STEP_BY_STEP_FIX.sql" "reports"
call :moveFile "STORAGE_DATABASE_COMPARISON_REPORT.md" "reports"
call :moveFile "STORAGE_SYNC_REPORT.md" "reports"
call :moveFile "STUDENT_DASHBOARD_AUDIT_REPORT.md" "reports"
call :moveFile "STUDENT_DASHBOARD_FIXES_SUMMARY.md" "reports"
call :moveFile "TABLE_ANALYSIS_AND_FIX_GUIDE.md" "reports"
call :moveFile "TEACHER_STUDENT_FEATURES_AUDIT_REPORT.md" "reports"
call :moveFile "TEST_COD_NOW.md" "reports"
call :moveFile "TEST_SERIES_COUPON_IMPLEMENTATION.md" "reports"
call :moveFile "TEST_SERIES_ORDER_INTEGRATION_SUMMARY.md" "reports"
call :moveFile "TROUBLESHOOTING.md" "reports"
call :moveFile "TUTOR_DASHBOARD_AUDIT_REPORT.md" "reports"
call :moveFile "TUTOR_DASHBOARD_FIXES_SUMMARY.md" "reports"
call :moveFile "UI_FIXES_SUMMARY.md" "reports"
call :moveFile "URGENT_FIX.md" "reports"
call :moveFile "VERIFY_AND_FIX.md" "reports"
call :moveFile "VERIFY_INVOICE_FIXES.md" "reports"
call :moveFile "VIEWS_RESOURCES_AUDIT_REPORT.md" "reports"

:: Text/JSON reference files
call :moveFile "routes_full.json" "reports"
call :moveFile "routes_list.json" "reports"
call :moveFile "routes_list.txt" "reports"
call :moveFile "admin_routes.txt" "reports"
call :moveFile "defined_routes.txt" "reports"
call :moveFile "defined_admin_routes.txt" "reports"
call :moveFile "missing_routes.txt" "reports"
call :moveFile "view_route_names.txt" "reports"
call :moveFile "route_references.txt" "reports"
call :moveFile "old_files.txt" "reports"
call :moveFile "new_files.txt" "reports"
call :moveFile "old_important.txt" "reports"
call :moveFile "new_important.txt" "reports"
call :moveFile "current_db_files.txt" "reports"

echo.
echo Moving SQL files...
call :moveFile "ADD_ALL_MISSING_COLUMNS.sql" "sql"
call :moveFile "ADD_COLUMNS_DIRECT.sql" "sql"
call :moveFile "ADD_EMI_ADMIN_ADJUSTMENT_COLUMNS.sql" "sql"
call :moveFile "ADD_EMI_ENABLED_COLUMN.sql" "sql"
call :moveFile "ADD_EMI_PAYMENT_STATUS_COLUMNS.sql" "sql"
call :moveFile "ADD_INVOICE_COLUMNS_SIMPLE.sql" "sql"
call :moveFile "ADD_INVOICE_DATE_COLUMN.sql" "sql"
call :moveFile "ADD_MISSING_COLUMNS_FIX.sql" "sql"
call :moveFile "ADD_MISSING_COLUMNS_SAFE.sql" "sql"
call :moveFile "ADD_ORDER_COLUMNS.sql" "sql"
call :moveFile "ADD_ORDER_COLUMNS_SIMPLE.sql" "sql"
call :moveFile "ADD_ORDER_ID_COLUMN_FIX.sql" "sql"
call :moveFile "ADD_ORDER_PRICING_COLUMNS.sql" "sql"
call :moveFile "ADD_TEST_SERIES_COUPON_FIELDS.sql" "sql"
call :moveFile "ALTERNATIVE_FIX_WITH_DEFAULT.sql" "sql"
call :moveFile "ASSIGN_TUTOR_TO_BATCH.sql" "sql"
call :moveFile "CHECK_AND_FIX_TABLES.sql" "sql"
call :moveFile "CHECK_ASSIGNMENT_DATA.sql" "sql"
call :moveFile "CHECK_NOTE_CATEGORIES.sql" "sql"
call :moveFile "CHECK_STATUS.sql" "sql"
call :moveFile "COMPLETE_DATABASE_FIX.sql" "sql"
call :moveFile "COMPLETE_FIX.sql" "sql"
call :moveFile "COMPLETE_ORDER_TABLE_FIX.sql" "sql"
call :moveFile "CORRECT_INSERT.sql" "sql"
call :moveFile "CREATE_ALL_MISSING_TABLES.sql" "sql"
call :moveFile "CREATE_MOCK_TABLES.sql" "sql"
call :moveFile "CREATE_MOCK_TESTS_FIX.sql" "sql"
call :moveFile "CREATE_MOCK_TESTS_TABLE.sql" "sql"
call :moveFile "create_admin_simple.sql" "sql"
call :moveFile "create_admin_user.sql" "sql"
call :moveFile "create_admin_with_all_columns.sql" "sql"
call :moveFile "DEBUG_TESTS_QUERIES.sql" "sql"
call :moveFile "diagnose_questions_table.sql" "sql"
call :moveFile "EMERGENCY_FIX.sql" "sql"
call :moveFile "FIND_MISSING_TABLES.sql" "sql"
call :moveFile "FIX_BATCH_EXAM_TABLE.sql" "sql"
call :moveFile "FIX_COLUMN_TYPE.sql" "sql"
call :moveFile "FIX_ENHANCED_REPORT_CONTROLLER.sql" "sql"
call :moveFile "FIX_GST_REPORTS.sql" "sql"
call :moveFile "FIX_INVOICE_DATE_NOW.sql" "sql"
call :moveFile "FIX_INVOICE_TABLE.sql" "sql"
call :moveFile "FIX_MISSING_ROLES.sql" "sql"
call :moveFile "FIX_MISSING_TABLES.sql" "sql"
call :moveFile "FIX_NOW.sql" "sql"
call :moveFile "FIX_QUESTIONS_DELETED_AT_FINAL.sql" "sql"
call :moveFile "FIX_QUESTIONS_SIMPLE.sql" "sql"
call :moveFile "FIX_SUPERVISOR_ROLE.sql" "sql"
call :moveFile "FIX_TEST_SERIES_COLUMNS.sql" "sql"
call :moveFile "FIX_TEST_SERIES_TABLE.sql" "sql"
call :moveFile "FIX_VIDEO_LINKS.sql" "sql"
call :moveFile "get_tables_sql.sql" "sql"
call :moveFile "IMMEDIATE_FIX.sql" "sql"
call :moveFile "import_users_only.sql" "sql"
call :moveFile "QUICK_FIX_ALL_COLUMNS.sql" "sql"
call :moveFile "QUICK_FIX_ORDERS_PAGE.sql" "sql"
call :moveFile "QUICK_FIX_ORDER_COLUMNS.sql" "sql"
call :moveFile "QUICK_FIX_SUBSCRIPTION.sql" "sql"
call :moveFile "REMOVE_DELETED_AT_COLUMN.sql" "sql"
call :moveFile "REMOVE_DELETED_AT_SAFE.sql" "sql"
call :moveFile "REMOVE_DELETED_AT_SIMPLE.sql" "sql"
call :moveFile "rollback_questions_deleted_at.sql" "sql"
call :moveFile "RUN_THIS_FIRST.sql" "sql"
call :moveFile "RUN_THIS_FIX.sql" "sql"
call :moveFile "RUN_THIS_NOW.sql" "sql"
call :moveFile "SIMPLE_DEFINITIVE_FIX.sql" "sql"
call :moveFile "SIMPLE_FIX.sql" "sql"
call :moveFile "SOLUTION_IF_COLUMN_EXISTS.sql" "sql"
call :moveFile "UPGRADE_QUESTIONS_TABLE.sql" "sql"
call :moveFile "URGENT_FIX_ALL_INVOICE_COLUMNS.sql" "sql"
call :moveFile "URGENT_FIX_SQL.sql" "sql"
call :moveFile "VERIFY_COLUMN.sql" "sql"
call :moveFile "VERIFY_DATABASE_SCHEMA.sql" "sql"
call :moveFile "VERIFY_MODEL_FIELDS.sql" "sql"

echo.
echo Moving image assets...
call :moveFile "batch-progress-fixed.png" "assets"
call :moveFile "contact_page_current.png" "assets"
call :moveFile "contact_page_full.png" "assets"
call :moveFile "contact_page_new_design.png" "assets"
call :moveFile "contact_page_with_header.png" "assets"
call :moveFile "homepage_fixed.png" "assets"
call :moveFile "homepage_working.png" "assets"
call :moveFile "lessons_page_debug.png" "assets"

echo.
echo Moving scripts...
call :moveFile "cleanup_and_push.sh" "scripts"
call :moveFile "fresh_push.sh" "scripts"
call :moveFile "fix-storage.sh" "scripts"
call :moveFile "compare_app_directories.php" "scripts"
call :moveFile "compare_databases.php" "scripts"
call :moveFile "compare_databases_manual.php" "scripts"
call :moveFile "compare_dirs.py" "scripts"
call :moveFile "compare_projects.php" "scripts"
call :moveFile "compare_resources.php" "scripts"
call :moveFile "compare_resources.py" "scripts"
call :moveFile "compare_resources_node.js" "scripts"
call :moveFile "compare_routes.php" "scripts"
call :moveFile "create_missing_tables.php" "scripts"
call :moveFile "check_old_codebase.php" "scripts"
call :moveFile "check_tables.php" "scripts"
call :moveFile "extract_routes.php" "scripts"
call :moveFile "extract_routes.py" "scripts"
call :moveFile "get_defined_routes.php" "scripts"
call :moveFile "run_mock_migration.php" "scripts"
call :moveFile "VERIFY_FIXES.php" "scripts"
call :moveFile "fix_syntax.php" "scripts"
call :moveFile "update-dependencies.php" "scripts"
call :moveFile "search_directory.php" "scripts"
call :moveFile "debug2.php" "scripts"
call :moveFile "debug3.php" "scripts"
call :moveFile "debug_routes.php" "scripts"

echo.
echo ==========================================
echo SUMMARY
echo ==========================================
echo Reports moved: %REPORTS%
echo SQL files moved: %SQL%
echo Assets moved: %ASSETS%
echo Scripts moved: %SCRIPTS%
set /a TOTAL=%REPORTS%+%SQL%+%ASSETS%+%SCRIPTS%
echo Total files moved: %TOTAL%
echo.
echo Organization complete!
echo.
pause
exit /b

:moveFile
if exist "%BASE_DIR%\%~1" (
    move /Y "%BASE_DIR%\%~1" "%BASE_DIR%\_archive\%~2\" >nul
    if "%~2"=="reports" set /a REPORTS+=1
    if "%~2"=="sql" set /a SQL+=1
    if "%~2"=="assets" set /a ASSETS+=1
    if "%~2"=="scripts" set /a SCRIPTS+=1
    echo   Moved: %~1
) else (
    echo   Skipped (not found): %~1
)
exit /b
