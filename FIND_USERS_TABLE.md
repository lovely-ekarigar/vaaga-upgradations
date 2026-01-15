# Users Table खोजने के तरीके

## Problem
phpMyAdmin में `users` table नहीं दिख रही है, लेकिन code में यह table use हो रही है।

## Solutions

### 1. **phpMyAdmin में Search करें**
phpMyAdmin में top पर "Filters" section में search box है:
- "Containing the word:" field में `users` type करें
- या browser में Ctrl+F press करके `users` search करें

### 2. **Alphabetically Scroll करें**
Table list alphabetically sorted है। 'u' section में scroll करें:
- `user_addresses`
- `user_bank_details` 
- `user_courses`
- `user_login_logs`
- **`users`** ← यहाँ होनी चाहिए

### 3. **SQL Query से Check करें**
phpMyAdmin में "SQL" tab पर जाएं और यह query run करें:

```sql
SHOW TABLES LIKE 'users';
```

या

```sql
SELECT TABLE_NAME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'vaaga_elearn' 
AND TABLE_NAME = 'users';
```

### 4. **Direct SQL Query से Table Structure देखें**
```sql
DESCRIBE users;
```

या

```sql
SHOW CREATE TABLE users;
```

### 5. **Check करें कि Table Actually Exist करती है या नहीं**
```sql
SELECT COUNT(*) as table_exists
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'vaaga_elearn' 
AND TABLE_NAME = 'users';
```

अगर result `1` आए तो table exist करती है।

---

## Expected Table Structure

Migration file के according, `users` table में ये columns होने चाहिए:

### Main Columns:
- `id` - Primary key (increments)
- `uuid` - UUID
- `first_name` - First name
- `last_name` - Last name
- `email` - Email (unique)
- `avatar_type` - Avatar type (default: 'gravatar')
- `avatar_location` - Avatar file location
- `password` - Hashed password
- `password_changed_at` - Password change timestamp
- `active` - Active status (tinyInteger, default: 1)
- `confirmation_code` - Email confirmation code
- `confirmed` - Confirmed status (boolean)
- `timezone` - User timezone
- `last_login_at` - Last login timestamp
- `last_login_ip` - Last login IP address
- `remember_token` - Remember me token
- `created_at` - Created timestamp
- `updated_at` - Updated timestamp
- `deleted_at` - Soft delete timestamp

### Additional Columns (from migration 2019_06_07):
- `dob` - Date of birth
- `phone` - Phone number
- `gender` - Gender
- `address` - Address
- `city` - City
- `pincode` - Pincode
- `state` - State
- `country` - Country

---

## Migration Files Location

1. **Main Migration:**
   - `database/migrations/2014_10_12_000000_create_users_table.php`

2. **Additional Columns:**
   - `database/migrations/2019_06_07_073739_add_columns_in_users_table.php`

---

## Model Location

- **Model:** `app/Models/Auth/User.php`
- **Namespace:** `App\Models\Auth\User`

---

## Quick Check Commands

### Laravel Tinker से Check करें:
```bash
php artisan tinker
```

फिर:
```php
DB::table('users')->count();
// या
\App\Models\Auth\User::count();
```

### Migration Status Check:
```bash
php artisan migrate:status
```

यह show करेगा कि `create_users_table` migration run हुई है या नहीं।

---

## अगर Table नहीं मिल रही है:

### Option 1: Migration Run करें
```bash
php artisan migrate
```

या specific migration:
```bash
php artisan migrate --path=/database/migrations/2014_10_12_000000_create_users_table.php
```

### Option 2: Fresh Migration (⚠️ Warning: यह सभी data delete कर देगा)
```bash
php artisan migrate:fresh
```

### Option 3: Database में Direct Check करें
phpMyAdmin में "SQL" tab पर जाएं:
```sql
-- सभी tables list करें जिनमें 'user' word है
SHOW TABLES LIKE '%user%';

-- Users table की structure देखें (अगर exist करती है)
SHOW COLUMNS FROM users;
```

---

## Important Notes

1. **Table Name:** Config file में `config/access.php` में table name `'users' => 'users'` set है
2. **Model:** `App\Models\Auth\User` model `users` table use करती है
3. **Relationships:** 
   - `orders` table में `user_id` foreign key है जो `users.id` को point करती है
   - `Order` model में `belongsTo(User::class)` relationship है

---

## Troubleshooting

अगर table अभी भी नहीं मिल रही है:

1. **Check करें कि सही database select है:**
   - phpMyAdmin में left sidebar में `vaaga_elearn` database selected होनी चाहिए

2. **Browser में Search करें:**
   - phpMyAdmin page पर Ctrl+F press करें
   - `users` search करें

3. **Table Count Check करें:**
   - phpMyAdmin में bottom पर total tables count दिखता है
   - अगर 93+ tables हैं, तो scroll करके देखें

4. **SQL Query से Direct Access:**
   ```sql
   SELECT * FROM users LIMIT 10;
   ```
   अगर यह query work करती है, तो table exist करती है।

---

**Note:** Image description के according, `users` table database में exist करती है और 10 rows हैं। हो सकता है कि आपको phpMyAdmin में scroll करके 'u' section में देखना पड़े।




