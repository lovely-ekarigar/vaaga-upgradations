-- ============================================
-- QUICK FIX: Update Subscription End Date
-- Student: abhishek shrivastava
-- Email: abhishek6june2000@gmail.com
-- New End Date: 2026-05-15
-- ============================================

-- Step 1: Find User ID (Expected: 1290)
SELECT id, first_name, last_name, email 
FROM users 
WHERE email = 'abhishek6june2000@gmail.com';

-- Step 2: Find All Orders for This User
SELECT 
    o.id as order_id,
    o.reference_no,
    o.amount,
    o.course_mode,
    o.end_date as current_end_date,
    o.status,
    o.created_at,
    oi.item_id,
    c.title as course_name
FROM orders o
LEFT JOIN order_items oi ON o.id = oi.order_id
LEFT JOIN courses c ON oi.item_id = c.id
WHERE o.user_id = (
    SELECT id FROM users WHERE email = 'abhishek6june2000@gmail.com'
)
AND o.status = 1
ORDER BY o.id DESC;

-- Step 3: UPDATE THE END DATE
-- ⚠️ IMPORTANT: Replace [ORDER_ID] with the actual order ID from Step 2
-- If multiple orders, update the one with course "Class 4 Maths Olympiad"

UPDATE orders 
SET end_date = '2026-05-15',
    updated_at = NOW()
WHERE id = [ORDER_ID]  -- ⚠️ REPLACE THIS WITH ACTUAL ORDER ID
AND user_id = (
    SELECT id FROM users WHERE email = 'abhishek6june2000@gmail.com'
);

-- Step 4: Verify the Update
SELECT 
    id,
    reference_no,
    end_date,
    course_mode,
    status,
    updated_at
FROM orders
WHERE id = [ORDER_ID];  -- ⚠️ REPLACE THIS WITH ACTUAL ORDER ID

-- ============================================
-- ALTERNATIVE: Update All Active Monthly Subscriptions for This User
-- (Use only if you're sure this is the correct approach)
-- ============================================
/*
UPDATE orders 
SET end_date = '2026-05-15',
    updated_at = NOW()
WHERE user_id = (
    SELECT id FROM users WHERE email = 'abhishek6june2000@gmail.com'
)
AND status = 1
AND course_mode LIKE '%monthly%';
*/




