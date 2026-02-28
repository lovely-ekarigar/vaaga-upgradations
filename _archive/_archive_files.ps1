# File Organization Script for Vaaga Academy
# Run this script in PowerShell: .\_archive_files.ps1

$ErrorActionPreference = 'Continue'
$baseDir = 'c:\Projects\vaagaacademy'

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "File Organization Script for Vaaga Academy" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Create directories
Write-Host "Creating directory structure..." -ForegroundColor Yellow
$dirs = @('_archive/reports', '_archive/sql', '_archive/assets', '_archive/scripts')
foreach ($dir in $dirs) {
    $path = Join-Path $baseDir $dir
    if (!(Test-Path $path)) {
        New-Item -ItemType Directory -Path $path -Force | Out-Null
    }
}
Write-Host "Directories created." -ForegroundColor Green
Write-Host ""

# Define files to move
$filesToMove = @{
    '_archive/reports' = @(
        '1ON1_PRICING_IMPLEMENTATION_SUMMARY.md', 'ADMIN_DASHBOARD_AUDIT_REPORT.md', 'AGENTS.md',
        'app_comparison_final_report.md', 'app_comparison_summary.txt', 'APPLY_ALL_FIXES_NOW.md',
        'APPLY_FIX_NOW.md', 'ARCHITECTURAL_RECONCILIATION_REPORT.md', 'BEST_SOLUTION.md',
        'CKEditor_AND_DB_FIX.md', 'CKEDITOR_IMPLEMENTATION_COMPLETE.md', 'CKEDITOR_QUESTIONS_FIX.md',
        'CLEANUP_TEMP_FILES.md', 'COMPARE_AND_EXPORT.md', 'COMPLETE_FIXES_SUMMARY.md',
        'COMPLETE_FIX_GUIDE.md', 'COMPLETE_SOLUTION.md', 'COMPREHENSIVE_END_TO_END_AUDIT_REPORT.md',
        'COMPREHENSIVE_FEATURE_PARITY_AUDIT_FINAL.md', 'COMPREHENSIVE_ROUTE_CONTROLLER_AUDIT_REPORT.md',
        'CONFLICT_RESOLUTION_REPORT.md', 'contact_page_detailed.md', 'COPY_IMAGES_INSTRUCTIONS.md',
        'CORE_LMS_FEATURES_AUDIT_REPORT.md', 'COURSE_MODE_CHANGE_FEATURE.md', 'CRITICAL_FIXES_NEEDED.md',
        'CRITICAL_FIXES_SCRIPT.md', 'DASHBOARD_FIXES_APPLIED.md', 'DASHBOARD_FIXES_REPORT.md',
        'DATABASE_ANALYSIS_REPORT.md', 'DATABASE_COMPARISON_REPORT.md', 'DATA_DISPLAY_FIXES.md',
        'DEBUG_COD_ORDER.md', 'DEEP_COMPARISON_REPORT.md', 'DEPLOYMENT_GUIDE.md',
        'DEPLOYMENT_HOSTINGER.md', 'DEPLOY_INSTRUCTIONS.md', 'DEPLOY_TO_PRODUCTION.md',
        'DETAILED_ROUTE_RECONCILIATION_AUDIT_REPORT.md', 'EDGE_CASES_BUSINESS_LOGIC_AUDIT_REPORT.md',
        'EDGE_CASES_QUICK_REFERENCE.md', 'ELOQUENT_MODELS_AUDIT_REPORT.md', 'EMI_DASHBOARD_IMPLEMENTATION_SUMMARY.md',
        'EMI_DASHBOARD_SETUP.md', 'EMI_DISPLAY_UPDATE_SUMMARY.md', 'EMI_DUE_DATE_CALCULATION_FIX.md',
        'EMI_PAYMENT_TRACKING_SYSTEM.md', 'EMI_REMINDER_SYSTEM.md', 'EMI_TRANSPARENT_TRACKING_IMPLEMENTATION.md',
        'EXPECTED_TABLES_LIST.md', 'FEATURE_RESTORATION_SUMMARY.md', 'FINAL_CONFIG_AND_APP_COMPARISON.md',
        'FINAL_DELIVERY_SUMMARY.md', 'FINAL_FIXES_APPLIED.md', 'FINAL_FIX_HINDI.md',
        'FINAL_FIX_INSTRUCTIONS.md', 'FINAL_FIX_STEPS.md', 'FINAL_MODEL_COMPARISON_SUMMARY.md',
        'FINAL_SOLUTION.md', 'FINAL_SOLUTION_HINDI.md', 'FINAL_TESTING_CHECKLIST.md',
        'FIXES_APPLIED.md', 'FIXES_IMPLEMENTATION_SUMMARY.md', 'FIX_INSTRUCTIONS.md',
        'FIX_PAGE_EXPIRED.md', 'FIX_PRODUCTION.md', 'FIX_ROLE_ERROR.md',
        'FIX_TEST_SERIES_ORDER_ID_COLUMN.md', 'FRONTEND_AUDIT_REPORT.md', 'FRONTEND_AUDIT_SUMMARY.md',
        'FRONTEND_VIEWS_AUDIT_REPORT.md', 'GST_CALCULATION_LOGIC.md', 'GST_REPORTS_FIXES_SUMMARY.md',
        'HOW_TO_FIX.md', 'IMPLEMENTATION_COMPLETE.md', 'IMPLEMENTATION_SUMMARY.md',
        'INVOICE_FIX_CHECKLIST.md', 'INVOICE_FIX_SUMMARY.md', 'INVOICE_GENERATION_FIX_COMPLETE.md',
        'ISSUES_FIX_COMPLETE_SUMMARY.md', 'LARAVEL_CONFIG_AND_CRITICAL_FILES_AUDIT_REPORT.md',
        'MISSING_TABLES_SUMMARY.md', 'MOCKTEST_VIEWS_AUDIT_REPORT.md', 'MOCK_TABLES_MISSING_FIX.md',
        'MOCK_TESTS_FIX_SUMMARY.md', 'MOCK_TEST_MIGRATION_COMPLETE_GUIDE.md', 'MOCK_TEST_SERIES_CONTROLLER_AUDIT_REPORT.md',
        'MOCK_TEST_SERIES_MIGRATION_AUDIT_REPORT.md', 'MOCK_TEST_SERIES_MODELS_AUDIT_REPORT.md',
        'MOCK_TEST_SERIES_ROUTE_AUDIT_REPORT.md', 'MOCK_TEST_TUTOR_IMPLEMENTATION_GUIDE.md',
        'MODEL_COMPARISON_REPORT.md', 'NAVIGATION_PAGES_FIX_REPORT.md', 'PAYMENT_IMPROVEMENTS_IMPLEMENTATION_SUMMARY.md',
        'PAYMENT_LINK_IMPLEMENTATION_SUMMARY.md', 'PAYMENT_ORDER_AUDIT_REPORT.md', 'PAYMENT_ORDER_REFACTOR_COMPLETE_SUMMARY.md',
        'PERMANENT_FIX_CSRF.md', 'PRICING_CAPTURE_FIX_SUMMARY.md', 'PRODUCTION_DEPLOYMENT_CHECKLIST.md',
        'PROJECT_AUDIT_SUMMARY.md', 'PROJECT_COMPARISON_REPORT.md', 'PROJECT_FIXES_SUMMARY.md',
        'QUESTION_BANK_UPGRADE_SUMMARY.md', 'QUICK_CHECK_COMMANDS.md', 'README.md',
        'README_SOURCE.md', 'resources_comparison_report.md', 'ROUTES_API_AUDIT_REPORT.md',
        'ROUTE_FIXES_APPLIED.md', 'SETUP_LOCAL_ENVIRONMENT.md', 'SHADOW_FIELDS_ANALYSIS.md',
        'SIDEBAR_MENU_FIX_SUMMARY.md', 'SKILL_PAYMENT_IMPROVEMENTS.md', 'SOFTDELETES_REMOVED.md',
        'STEP_BY_STEP_FIX.sql', 'STORAGE_DATABASE_COMPARISON_REPORT.md', 'STORAGE_SYNC_REPORT.md',
        'STUDENT_DASHBOARD_AUDIT_REPORT.md', 'STUDENT_DASHBOARD_FIXES_SUMMARY.md', 'TABLE_ANALYSIS_AND_FIX_GUIDE.md',
        'TEACHER_STUDENT_FEATURES_AUDIT_REPORT.md', 'TEST_COD_NOW.md', 'TEST_SERIES_COUPON_IMPLEMENTATION.md',
        'TEST_SERIES_ORDER_INTEGRATION_SUMMARY.md', 'TROUBLESHOOTING.md', 'TUTOR_DASHBOARD_AUDIT_REPORT.md',
        'TUTOR_DASHBOARD_FIXES_SUMMARY.md', 'UI_FIXES_SUMMARY.md', 'URGENT_FIX.md',
        'VERIFY_AND_FIX.md', 'VERIFY_INVOICE_FIXES.md', 'VIEWS_RESOURCES_AUDIT_REPORT.md',
        # Text/JSON reference files
        'routes_full.json', 'routes_list.json', 'routes_list.txt',
        'admin_routes.txt', 'defined_routes.txt', 'defined_admin_routes.txt', 'missing_routes.txt',
        'view_route_names.txt', 'route_references.txt', 'old_files.txt', 'new_files.txt',
        'old_important.txt', 'new_important.txt', 'current_db_files.txt'
    )
    '_archive/sql' = @(
        'ADD_ALL_MISSING_COLUMNS.sql', 'ADD_COLUMNS_DIRECT.sql', 'ADD_EMI_ADMIN_ADJUSTMENT_COLUMNS.sql',
        'ADD_EMI_ENABLED_COLUMN.sql', 'ADD_EMI_PAYMENT_STATUS_COLUMNS.sql', 'ADD_INVOICE_COLUMNS_SIMPLE.sql',
        'ADD_INVOICE_DATE_COLUMN.sql', 'ADD_MISSING_COLUMNS_FIX.sql', 'ADD_MISSING_COLUMNS_SAFE.sql',
        'ADD_ORDER_COLUMNS.sql', 'ADD_ORDER_COLUMNS_SIMPLE.sql', 'ADD_ORDER_ID_COLUMN_FIX.sql',
        'ADD_ORDER_PRICING_COLUMNS.sql', 'ADD_TEST_SERIES_COUPON_FIELDS.sql', 'ALTERNATIVE_FIX_WITH_DEFAULT.sql',
        'ASSIGN_TUTOR_TO_BATCH.sql', 'CHECK_AND_FIX_TABLES.sql', 'CHECK_ASSIGNMENT_DATA.sql',
        'CHECK_NOTE_CATEGORIES.sql', 'CHECK_STATUS.sql', 'COMPLETE_DATABASE_FIX.sql',
        'COMPLETE_FIX.sql', 'COMPLETE_ORDER_TABLE_FIX.sql', 'CORRECT_INSERT.sql',
        'CREATE_ALL_MISSING_TABLES.sql', 'CREATE_MOCK_TABLES.sql', 'CREATE_MOCK_TESTS_FIX.sql',
        'CREATE_MOCK_TESTS_TABLE.sql', 'create_admin_simple.sql', 'create_admin_user.sql',
        'create_admin_with_all_columns.sql', 'DEBUG_TESTS_QUERIES.sql', 'diagnose_questions_table.sql',
        'EMERGENCY_FIX.sql', 'FIND_MISSING_TABLES.sql', 'FIX_BATCH_EXAM_TABLE.sql',
        'FIX_COLUMN_TYPE.sql', 'FIX_ENHANCED_REPORT_CONTROLLER.sql', 'FIX_GST_REPORTS.sql',
        'FIX_INVOICE_DATE_NOW.sql', 'FIX_INVOICE_TABLE.sql', 'FIX_MISSING_ROLES.sql',
        'FIX_MISSING_TABLES.sql', 'FIX_NOW.sql', 'FIX_QUESTIONS_DELETED_AT_FINAL.sql',
        'FIX_QUESTIONS_SIMPLE.sql', 'FIX_SUPERVISOR_ROLE.sql', 'FIX_TEST_SERIES_COLUMNS.sql',
        'FIX_TEST_SERIES_TABLE.sql', 'FIX_VIDEO_LINKS.sql', 'get_tables_sql.sql',
        'IMMEDIATE_FIX.sql', 'import_users_only.sql', 'QUICK_FIX_ALL_COLUMNS.sql',
        'QUICK_FIX_ORDERS_PAGE.sql', 'QUICK_FIX_ORDER_COLUMNS.sql', 'QUICK_FIX_SUBSCRIPTION.sql',
        'REMOVE_DELETED_AT_COLUMN.sql', 'REMOVE_DELETED_AT_SAFE.sql', 'REMOVE_DELETED_AT_SIMPLE.sql',
        'rollback_questions_deleted_at.sql', 'RUN_THIS_FIRST.sql', 'RUN_THIS_FIX.sql',
        'RUN_THIS_NOW.sql', 'SIMPLE_DEFINITIVE_FIX.sql', 'SIMPLE_FIX.sql',
        'SOLUTION_IF_COLUMN_EXISTS.sql', 'UPGRADE_QUESTIONS_TABLE.sql', 'URGENT_FIX_ALL_INVOICE_COLUMNS.sql',
        'URGENT_FIX_SQL.sql', 'VERIFY_COLUMN.sql', 'VERIFY_DATABASE_SCHEMA.sql',
        'VERIFY_MODEL_FIELDS.sql'
    )
    '_archive/assets' = @(
        'batch-progress-fixed.png', 'contact_page_current.png', 'contact_page_full.png',
        'contact_page_new_design.png', 'contact_page_with_header.png', 'homepage_fixed.png',
        'homepage_working.png', 'lessons_page_debug.png'
    )
    '_archive/scripts' = @(
        'cleanup_and_push.sh', 'fresh_push.sh', 'fix-storage.sh',
        'compare_app_directories.php', 'compare_databases.php', 'compare_databases_manual.php',
        'compare_dirs.py', 'compare_projects.php', 'compare_resources.php',
        'compare_resources.py', 'compare_resources_node.js', 'compare_routes.php',
        'create_missing_tables.php', 'check_old_codebase.php', 'check_tables.php',
        'extract_routes.php', 'extract_routes.py', 'get_defined_routes.php',
        'run_mock_migration.php', 'VERIFY_FIXES.php', 'fix_syntax.php',
        'update-dependencies.php', 'search_directory.php', 'debug2.php',
        'debug3.php', 'debug_routes.php'
    )
}

$moved = @{ 'reports' = 0; 'sql' = 0; 'assets' = 0; 'scripts' = 0 }
$notFound = @()

foreach ($destDir in $filesToMove.Keys) {
    $files = $filesToMove[$destDir]
    $category = $destDir.Split('/')[-1]
    
    Write-Host "Moving $category files..." -ForegroundColor Yellow
    
    foreach ($filename in $files) {
        $src = Join-Path $baseDir $filename
        $dest = Join-Path (Join-Path $baseDir $destDir) $filename
        
        if (Test-Path $src) {
            try {
                Move-Item -Path $src -Destination $dest -Force
                Write-Host "  Moved: $filename" -ForegroundColor Gray
                $moved[$category]++
            } catch {
                Write-Host "  Error moving ${filename}: $_" -ForegroundColor Red
            }
        } else {
            $notFound += $filename
        }
    }
    Write-Host ""
}

# Summary
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "SUMMARY" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "Reports moved: $($moved['reports'])" -ForegroundColor Green
Write-Host "SQL files moved: $($moved['sql'])" -ForegroundColor Green
Write-Host "Assets moved: $($moved['assets'])" -ForegroundColor Green
Write-Host "Scripts moved: $($moved['scripts'])" -ForegroundColor Green
$total = $moved['reports'] + $moved['sql'] + $moved['assets'] + $moved['scripts']
Write-Host "Total files moved: $total" -ForegroundColor Green
Write-Host "Files not found (skipped): $($notFound.Count)" -ForegroundColor Yellow

if ($notFound.Count -gt 0) {
    Write-Host ""
    Write-Host "Not found:" -ForegroundColor Yellow
    foreach ($f in $notFound) {
        Write-Host "  - $f" -ForegroundColor DarkGray
    }
}

Write-Host ""
Write-Host "Organization complete!" -ForegroundColor Green
