# Update Subscription End Date - Solution Guide

## Issue
Student: **abhishek shrivastava** (abhishek6june2000@gmail.com)  
Problem: "Your Subscription has been expired" message showing on dashboard  
Solution: Update subscription `end_date` to **15-May-2026**

---

## Root Cause

The expiration check is done in:
- **Model:** `app/Models/Course.php` - Method: `isCourseExpired()`
- **Logic:** Compares current date with `orders.end_date`
- **Check:** `if(date("Y-m-d") > date("Y-m-d", strtotime($order->end_date)))`

The `end_date` field in the `orders` table is in the past, causing the expiration message.

---

## Solution Steps

### Step 1: Find the User and Order

First, identify the user ID and order ID:

```sql
-- Find user ID
SELECT id, first_name, last_name, email 
FROM users 
WHERE email = 'abhishek6june2000@gmail.com';

-- Expected result: id = 1290 (from image)
```

```sql
-- Find all orders for this user
SELECT 
    o.id as order_id,
    o.reference_no,
    o.amount,
    o.course_mode,
    o.status,
    o.end_date,
    o.created_at,
    o.updated_at
FROM orders o
WHERE o.user_id = 1290
AND o.status = 1
ORDER BY o.id DESC;
```

### Step 2: Identify the Correct Order

Check which order contains the course "Class 4 Maths Olympiad":

```sql
-- Find order with specific course
SELECT 
    o.id as order_id,
    o.reference_no,
    o.end_date,
    oi.item_id,
    c.title as course_title
FROM orders o
INNER JOIN order_items oi ON o.id = oi.order_id
INNER JOIN courses c ON oi.item_id = c.id
WHERE o.user_id = 1290
AND o.status = 1
AND (c.title LIKE '%Olympiad%' OR c.title LIKE '%Class 4%' OR c.title LIKE '%Maths%')
ORDER BY o.id DESC;
```

### Step 3: Update the End Date

Once you have the order ID, update the `end_date`:

```sql
-- Update end_date to 15-May-2026
UPDATE orders 
SET end_date = '2026-05-15',
    updated_at = NOW()
WHERE id = [ORDER_ID]
AND user_id = 1290;
```

**Example (if order_id is 76):**
```sql
UPDATE orders 
SET end_date = '2026-05-15',
    updated_at = NOW()
WHERE id = 76
AND user_id = 1290;
```

### Step 4: Verify the Update

```sql
-- Verify the update
SELECT 
    id,
    reference_no,
    end_date,
    course_mode,
    status,
    updated_at
FROM orders
WHERE id = [ORDER_ID]
AND user_id = 1290;
```

---

## Complete SQL Script (All-in-One)

```sql
-- Step 1: Find user
SELECT id, email, first_name, last_name 
FROM users 
WHERE email = 'abhishek6june2000@gmail.com';

-- Step 2: Find orders for this user (replace 1290 with actual user_id)
SELECT 
    o.id as order_id,
    o.reference_no,
    o.amount,
    o.course_mode,
    o.end_date as current_end_date,
    o.status,
    oi.item_id,
    c.title as course_name
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
LEFT JOIN courses c ON oi.item_id = c.id
WHERE o.user_id = 1290
AND o.status = 1
ORDER BY o.id DESC;

-- Step 3: Update end_date (replace ORDER_ID with actual order ID)
UPDATE orders 
SET end_date = '2026-05-15',
    updated_at = NOW()
WHERE id = [ORDER_ID]
AND user_id = 1290;

-- Step 4: Verify
SELECT 
    id,
    reference_no,
    end_date,
    course_mode,
    status
FROM orders
WHERE id = [ORDER_ID];
```

---

## Alternative: Update via phpMyAdmin

1. **Login to phpMyAdmin**
2. **Select Database:** `u372758074_elearn` (or your database name)
3. **Go to `orders` table**
4. **Click "Browse" or "SQL" tab**
5. **Run this query:**

```sql
UPDATE orders 
SET end_date = '2026-05-15'
WHERE user_id = (
    SELECT id FROM users WHERE email = 'abhishek6june2000@gmail.com'
)
AND status = 1
AND course_mode LIKE '%monthly%';
```

**⚠️ Warning:** This will update ALL active monthly subscriptions for this user. If you want to update only a specific order, use the order ID instead.

---

## Backend Update Method (Laravel)

If you want to create a backend method to update this:

### Option 1: Add to OrderController

```php
// In app/Http/Controllers/Backend/Admin/OrderController.php

public function updateSubscriptionEndDate(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);
    
    $request->validate([
        'end_date' => 'required|date|after:today'
    ]);
    
    $order->end_date = $request->end_date;
    $order->save();
    
    return redirect()->back()
        ->withFlashSuccess("Subscription end date updated to " . date('d M Y', strtotime($request->end_date)));
}
```

### Option 2: Direct Database Update via Tinker

```bash
php artisan tinker
```

```php
// Find user
$user = \App\Models\Auth\User::where('email', 'abhishek6june2000@gmail.com')->first();

// Find orders
$orders = \App\Models\Order::where('user_id', $user->id)
    ->where('status', 1)
    ->get();

// Update end_date
foreach($orders as $order) {
    $order->end_date = '2026-05-15';
    $order->save();
    echo "Updated Order ID: " . $order->id . "\n";
}
```

---

## Important Notes

1. **Date Format:** Use `YYYY-MM-DD` format (e.g., `2026-05-15`)
2. **Multiple Orders:** If user has multiple orders, update the correct one (check course name)
3. **Status Check:** Only update orders with `status = 1` (completed)
4. **Course Mode:** Check if it's a monthly subscription (`course_mode LIKE '%monthly%'`)
5. **Backup:** Always backup database before making changes

---

## Verification After Update

1. **Check Dashboard:** Student should no longer see expiration message
2. **Check Database:**
   ```sql
   SELECT end_date FROM orders WHERE id = [ORDER_ID];
   ```
3. **Test Access:** Student should be able to access classes

---

## Related Files

- **Model:** `app/Models/Course.php` - `isCourseExpired()` method (line 64-87)
- **Controller:** `app/Http/Controllers/Backend/Admin/MyclassController.php` - `studentClasses()` method (line 530)
- **View:** `resources/views/backend/myclass/studentclasses.blade.php` - Line 44, 65
- **Table:** `orders` - Column: `end_date`

---

## Quick Fix SQL (If you know the order ID)

```sql
-- Replace 76 with actual order ID
UPDATE orders 
SET end_date = '2026-05-15',
    updated_at = NOW()
WHERE id = 76;
```

---

## Prevention for Future

To prevent this issue in future:
1. Ensure payment processing properly updates `end_date`
2. Add validation in payment success callback
3. Consider adding admin interface to update subscription dates
4. Add logging for subscription updates

---

**Status:** ✅ Ready to execute  
**Priority:** 🔴 Urgent (Class at 7pm today)  
**Estimated Time:** 2-5 minutes




