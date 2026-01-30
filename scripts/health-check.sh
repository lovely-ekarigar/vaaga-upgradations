#!/bin/bash
#
# Health Check Script for VaagaAcademy
# Usage: bash scripts/health-check.sh [BASE_URL]
#
# This script checks the HTTP status of critical admin routes.
# For authenticated routes, you'll need to either:
#   1. Run from a logged-in session with cookies
#   2. Accept 302 redirects as "auth required, not broken"
#

BASE_URL="${1:-http://localhost:8000}"

echo "========================================"
echo "VaagaAcademy Health Check"
echo "Base URL: $BASE_URL"
echo "========================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

check_route() {
    local name="$1"
    local path="$2"
    local expected="${3:-200|302}"
    
    local url="${BASE_URL}${path}"
    local status=$(curl -s -o /dev/null -w "%{http_code}" -L --max-redirs 1 "$url" 2>/dev/null)
    
    if echo "$status" | grep -qE "^($expected)$"; then
        echo -e "${GREEN}[PASS]${NC} $name ($path) - Status: $status"
        return 0
    else
        echo -e "${RED}[FAIL]${NC} $name ($path) - Status: $status (expected: $expected)"
        return 1
    fi
}

echo "Checking Public Routes..."
echo "-------------------------"
check_route "Homepage" "/" "200"
check_route "Login Page" "/login" "200"
check_route "Register Page" "/register" "200"

echo ""
echo "Checking Admin Routes (expect 302 redirect if not authenticated)..."
echo "-------------------------------------------------------------------"
check_route "Dashboard" "/user/dashboard" "200|302"
check_route "Question Bank" "/user/questions-bank" "200|302"
check_route "Test Series" "/user/test-series" "200|302"
check_route "Mock Tests (Admin)" "/user/mocktests" "200|302"
check_route "Courses" "/user/courses" "200|302"
check_route "Categories" "/user/categories" "200|302"
check_route "Batches" "/user/batches" "200|302"
check_route "Students" "/user/students" "200|302"
check_route "Teachers" "/user/teachers" "200|302"
check_route "Orders" "/user/orders" "200|302"

echo ""
echo "Checking Student Routes..."
echo "--------------------------"
check_route "Student Mock Tests Dashboard" "/user/student/mocktests" "200|302"

echo ""
echo "Checking Tutor Routes..."
echo "------------------------"
check_route "Tutor Mock Tests" "/user/tutor/mocktests/available" "200|302"
check_route "Tutor Scheduled Tests" "/user/tutor/mocktests/scheduled" "200|302"

echo ""
echo "========================================"
echo "Health Check Complete"
echo "========================================"
echo ""
echo "Legend:"
echo "  [PASS] - Route returned expected status"
echo "  [FAIL] - Route returned unexpected status (potential issue)"
echo ""
echo "Notes:"
echo "  - 302 is expected for authenticated routes when not logged in"
echo "  - 401/403 on authenticated routes may indicate permission issues"
echo "  - 500 indicates server error (check logs)"
