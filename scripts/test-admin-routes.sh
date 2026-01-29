#!/usr/bin/env bash
# Test HTTP status of key admin routes.
# Without auth: expect 302 (redirect to login) or 401.
# With valid session cookie: expect 200 for authorized users.
# Set BASE_URL (e.g. http://127.0.0.1:8000) and optionally COOKIE (Laravel session cookie).

BASE_URL="${BASE_URL:-http://127.0.0.1:8000}"
COOKIE="${COOKIE:-}"

if [ -n "$COOKIE" ]; then
  CURL_OPTS=(-b "$COOKIE" -s -o /dev/null -w "%{http_code}")
else
  CURL_OPTS=(-s -o /dev/null -w "%{http_code}")
fi

echo "Testing base: $BASE_URL"
echo ""

routes=(
  "/user/dashboard"
  "/user/questions-bank"
  "/user/test-series"
  "/user/mocktests"
)

for path in "${routes[@]}"; do
  code=$(curl "${CURL_OPTS[@]}" "$BASE_URL$path" -L 2>/dev/null || echo "000")
  echo "$path -> $code"
done

echo ""
echo "Expected without auth: 302 or 401. With admin session: 200."
