# Subscription Detail Page - Database Tables Information

## Overview
यह document subscription detail page पर दिखने वाली information के लिए उपयोग होने वाली सभी database tables का summary है।

## Main Tables Used

### 1. **`orders` Table** (Primary Table)
यह main table है जहाँ order की सभी basic information store होती है।

**Location:** `database/migrations/2019_01_24_120730_create_orders_table.php`

**Main Columns:**
- `id` - Order ID (Reference No. में ORD-{id} format में दिखता है)
- `user_id` - User जिसने order किया
- `reference_no` - Order reference number
- `amount` - Order amount (₹2600 जैसा)
- `payment_type` - Payment method (1=Stripe/Card, 2=PayPal, 3=Offline)
- `status` - Payment status (0=Pending, 1=Completed, 2=Failed)
- `course_mode` - Course subscription mode (e.g., "half_yearly", "monthly", etc.)
- `gst` - GST amount
- `coupon_id` - Applied coupon ID
- `transaction_id` - Payment transaction ID
- `remarks` - Additional remarks
- `invoice` - Invoice file path
- `total_cycle` - Total subscription cycles
- `paid_cycle` - Number of cycles paid
- `end_date` - Subscription end date
- `created_at` - Order date (28 Nov, 2025 | 12:23 PM जैसा)
- `updated_at` - Last updated timestamp

**Model:** `App\Models\Order`

**Controller Method:** `OrderController@show()` (line 439-445)

**View File:** `resources/views/backend/orders/show.blade.php`

---

### 2. **`order_items` Table**
यह table order में included items (courses/bundles) की information store करती है।

**Location:** `database/migrations/2019_01_24_120745_create_order_items_table.php`

**Main Columns:**
- `id` - Order item ID
- `order_id` - Foreign key to orders table
- `item_id` - Course/Bundle ID (polymorphic)
- `item_type` - Type (Course::class या Bundle::class)
- `price` - Item price
- `created_at` - Created timestamp
- `updated_at` - Updated timestamp

**Model:** `App\Models\OrderItem`

**Relationship:**
- `order()` - Belongs to Order
- `item()` - MorphTo relationship (Course या Bundle)

**Usage in View:**
```php
@foreach($order->items as $key=>$item)
    {{$crs->getCouseNameWithCat($item->item->id)}}
@endforeach
```

---

### 3. **`users` Table**
User की information (name, email) के लिए।

**Main Columns Used:**
- `id` - User ID
- `first_name` - First name
- `last_name` - Last name  
- `name` - Full name (abhishek shrivastava जैसा)
- `email` - Email address (abhishek6june2000@gmail.com जैसा)

**Model:** `App\Models\Auth\User`

**Relationship:**
- Order में `user()` relationship के through access होता है

**Usage in View:**
```php
{{$order->user->name}}
{{$order->user->email}}
```

---

### 4. **`courses` Table**
Course की detailed information के लिए (जो order_items में reference होती है)।

**Main Columns Used:**
- `id` - Course ID
- `title` - Course title
- Category information (via `getCouseNameWithCat()` method)

**Model:** `App\Models\Course`

**Usage:**
- Order items में course name display करने के लिए
- `Course::getCouseNameWithCat($item->item->id)` method से course name with category मिलता है

---

### 5. **`subscriptions` Table** (Optional - for subscription reports)
Monthly subscriptions की recurring payment information के लिए।

**Main Columns:**
- `id` - Subscription ID
- `order_id` - Foreign key to orders table
- `user_id` - User ID
- `amount` - Subscription amount
- `status` - Subscription status (1=Active)
- `created_at` - Subscription date
- `gst` - GST amount

**Model:** `App\Models\Subscription`

**Usage:**
- Subscription reports page में use होता है
- `OrderController@subscriptionDetails()` method में use होता है

---

## Data Flow

### Page Display Flow:
1. **Route:** `/user/orders/{id}` 
2. **Controller:** `OrderController@show($id)` 
3. **Query:** `Order::findOrFail($id)` - orders table से data fetch
4. **Relationships Load:**
   - `$order->user` - users table से user info
   - `$order->items` - order_items table से items
   - `$order->items->item` - courses/bundles table से item details
5. **View:** `backend/orders/show.blade.php` में render

### Information Displayed:

| Field | Source Table | Column |
|-------|-------------|--------|
| Reference No. | `orders` | `id` (formatted as ORD-{id}) |
| Ordered By Name | `users` | `name` |
| Ordered By Email | `users` | `email` |
| Items | `order_items` → `courses` | `item_id` + `getCouseNameWithCat()` |
| Amount | `orders` | `amount` |
| Course Mode | `orders` | `course_mode` |
| Payment Type | `orders` | `payment_type` |
| Payment Status | `orders` | `status` |
| Order Date | `orders` | `created_at` |

---

## Key Relationships

```php
Order Model:
- belongsTo(User::class) → users table
- hasMany(OrderItem::class) → order_items table
- hasOne(Invoice::class) → invoices table

OrderItem Model:
- belongsTo(Order::class) → orders table
- morphTo('item') → courses या bundles table

Subscription Model:
- belongsTo(User::class) → users table
- belongsTo(Order::class) → orders table (via order_id)
```

---

## Additional Notes

1. **Reference No. Format:** `ORD-{order_id}` (e.g., ORD-76)
2. **Course Mode Values:** 
   - `half_yearly` → "Half Yearly Subscription"
   - `monthly` → "Monthly Subscription"
   - `full` → "Full Course"
   - etc.
3. **Payment Type Mapping:**
   - `1` = Credit/Debit Card (Stripe Payment Gateway)
   - `2` = PayPal
   - `3` = Offline Payment
4. **Payment Status:**
   - `0` = Pending
   - `1` = Completed
   - `2` = Failed

---

## Files Reference

- **Controller:** `app/Http/Controllers/Backend/Admin/OrderController.php`
- **View:** `resources/views/backend/orders/show.blade.php`
- **Order Model:** `app/Models/Order.php`
- **OrderItem Model:** `app/Models/OrderItem.php`
- **Subscription Model:** `app/Models/Subscription.php`
- **Route:** `routes/backend/admin.php` (line 33 - resource route)

---

## SQL Query Example

यदि आप directly database query करना चाहें:

```sql
SELECT 
    o.id,
    CONCAT('ORD-', o.id) as reference_no,
    u.name as user_name,
    u.email as user_email,
    o.amount,
    o.course_mode,
    o.payment_type,
    o.status,
    o.created_at as order_date
FROM orders o
LEFT JOIN users u ON o.user_id = u.id
WHERE o.id = 76;

-- Order Items
SELECT 
    oi.id,
    oi.order_id,
    oi.item_id,
    oi.item_type,
    oi.price
FROM order_items oi
WHERE oi.order_id = 76;
```

---

**Note:** यह information image में दिखने वाले subscription detail page के based पर है जो `/user/orders/76` URL पर load होता है।




