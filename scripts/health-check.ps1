#
# Health Check Script for VaagaAcademy (PowerShell)
# Usage: .\scripts\health-check.ps1 [-BaseUrl "http://localhost:8000"]
#

param(
    [string]$BaseUrl = "http://localhost:8000"
)

Write-Host "========================================"
Write-Host "VaagaAcademy Health Check"
Write-Host "Base URL: $BaseUrl"
Write-Host "========================================"
Write-Host ""

function Check-Route {
    param(
        [string]$Name,
        [string]$Path,
        [string]$Expected = "200|302"
    )
    
    $url = "$BaseUrl$Path"
    try {
        $response = Invoke-WebRequest -Uri $url -Method GET -MaximumRedirection 0 -ErrorAction SilentlyContinue -UseBasicParsing
        $status = $response.StatusCode
    } catch {
        if ($_.Exception.Response) {
            $status = [int]$_.Exception.Response.StatusCode
        } else {
            $status = 0
        }
    }
    
    $expectedCodes = $Expected -split "\|"
    if ($expectedCodes -contains $status.ToString()) {
        Write-Host "[PASS] $Name ($Path) - Status: $status" -ForegroundColor Green
        return $true
    } else {
        Write-Host "[FAIL] $Name ($Path) - Status: $status (expected: $Expected)" -ForegroundColor Red
        return $false
    }
}

Write-Host "Checking Public Routes..."
Write-Host "-------------------------"
Check-Route -Name "Homepage" -Path "/" -Expected "200"
Check-Route -Name "Login Page" -Path "/login" -Expected "200"
Check-Route -Name "Register Page" -Path "/register" -Expected "200"

Write-Host ""
Write-Host "Checking Admin Routes (expect 302 redirect if not authenticated)..."
Write-Host "-------------------------------------------------------------------"
Check-Route -Name "Dashboard" -Path "/user/dashboard" -Expected "200|302"
Check-Route -Name "Question Bank" -Path "/user/questions-bank" -Expected "200|302"
Check-Route -Name "Test Series" -Path "/user/test-series" -Expected "200|302"
Check-Route -Name "Mock Tests (Admin)" -Path "/user/mocktests" -Expected "200|302"
Check-Route -Name "Courses" -Path "/user/courses" -Expected "200|302"
Check-Route -Name "Categories" -Path "/user/categories" -Expected "200|302"
Check-Route -Name "Batches" -Path "/user/batches" -Expected "200|302"
Check-Route -Name "Students" -Path "/user/students" -Expected "200|302"
Check-Route -Name "Teachers" -Path "/user/teachers" -Expected "200|302"
Check-Route -Name "Orders" -Path "/user/orders" -Expected "200|302"

Write-Host ""
Write-Host "Checking Student Routes..."
Write-Host "--------------------------"
Check-Route -Name "Student Mock Tests Dashboard" -Path "/user/student/mocktests" -Expected "200|302"

Write-Host ""
Write-Host "Checking Tutor Routes..."
Write-Host "------------------------"
Check-Route -Name "Tutor Mock Tests" -Path "/user/tutor/mocktests/available" -Expected "200|302"
Check-Route -Name "Tutor Scheduled Tests" -Path "/user/tutor/mocktests/scheduled" -Expected "200|302"

Write-Host ""
Write-Host "========================================"
Write-Host "Health Check Complete"
Write-Host "========================================"
Write-Host ""
Write-Host "Legend:"
Write-Host "  [PASS] - Route returned expected status"
Write-Host "  [FAIL] - Route returned unexpected status (potential issue)"
Write-Host ""
Write-Host "Notes:"
Write-Host "  - 302 is expected for authenticated routes when not logged in"
Write-Host "  - 401/403 on authenticated routes may indicate permission issues"
Write-Host "  - 500 indicates server error (check logs)"
