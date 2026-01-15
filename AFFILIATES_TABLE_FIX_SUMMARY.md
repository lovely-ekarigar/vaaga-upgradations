# Affiliates Table Fix Summary

## Date: 2025-01-15

## Issue Fixed

### Missing `affiliates` Table
**Error:** 
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'u372758074_elearn.affiliates' doesn't exist
(SQL: select * from `affiliates` where `code` is null limit ...)
```

**Root Cause:** 
- The `affiliates` table was missing from the database
- Used for affiliate marketing functionality
- Referenced in `CoursesController` when checking affiliate codes from cookies

## Fix Applied

### Created `affiliates` Table

**Table Structure:**
- `id` - INT AUTO_INCREMENT PRIMARY KEY
- `name` - VARCHAR(255) NOT NULL
- `email` - VARCHAR(255) NOT NULL UNIQUE
- `password` - VARCHAR(255) NOT NULL (stored as MD5 hash)
- `code` - VARCHAR(255) NOT NULL UNIQUE (affiliate referral code)
- `commision` - DECIMAL(5,2) NULL DEFAULT 0 (commission percentage)
- `total_earnings` - DECIMAL(10,2) NULL DEFAULT 0 (total earnings)
- `total_withdrawl` - DECIMAL(10,2) NULL DEFAULT 0 (total withdrawals)
- `bank_name` - VARCHAR(255) NULL (bank name for payouts)
- `bank_account` - VARCHAR(255) NULL (bank account number)
- `bank_ifsc` - VARCHAR(255) NULL (bank IFSC code)
- `bank_bef_name` - VARCHAR(255) NULL (bank account holder name)
- `created_at` - TIMESTAMP NULL
- `updated_at` - TIMESTAMP NULL

**Indexes:**
- `idx_code` on `code` column (for fast affiliate code lookups)
- `idx_email` on `email` column (for unique email constraint)

## Usage in Application

### Controllers Using Affiliates:
1. **CoursesController** (line 974-975):
   - Checks affiliate code from cookie
   - Used when displaying course pages

2. **AffiliateController**:
   - Handles affiliate registration, login, profile management
   - Manages affiliate earnings and withdrawals

3. **Backend\AffiliateController**:
   - Admin management of affiliates
   - Commission settings

### Views Using Affiliates:
- `resources/views/aff/index.blade.php` - Affiliate dashboard
- `resources/views/aff/earnings.blade.php` - Earnings display
- `resources/views/aff/withdrawl.blade.php` - Withdrawal requests
- `resources/views/aff/bank.blade.php` - Bank details form

## Testing

After fix:
- ✓ Affiliates table created successfully
- ✓ All required columns present
- ✓ Query test: OK (no errors)
- ✓ Course page can now query affiliates without errors

## Related Tables

- **`affiliate_withdrawls`** (or `aff_withdrawls`) - Stores withdrawal requests
- **`orders`** - Contains `aff_code` column linking orders to affiliates
- **`configs`** - Stores affiliate commission settings (`aff_commision`, `affiliate_user`)

## Next Steps

1. **Test Course Pages:**
   - Navigate to course pages
   - Should no longer show "affiliates table doesn't exist" error
   - Affiliate code cookies can be processed

2. **Optional: Create Affiliate Accounts:**
   - Use affiliate registration page to create test affiliates
   - Or create directly in database for testing

3. **Configure Affiliate Settings:**
   - Set `aff_commision` in `configs` table
   - Set `affiliate_user` in `configs` table

## Notes

- The `code` column is unique and used for affiliate referral links
- Password is stored as MD5 hash (legacy system)
- `total_earnings` is calculated from orders with matching `aff_code`
- `total_withdrawl` tracks total amount withdrawn
- Bank details are optional and used for payout processing
