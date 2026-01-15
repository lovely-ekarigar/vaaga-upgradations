<?php

// Create missing tables directly
$host = 'localhost';
$port = 3306;
$username = 'root';
$password = 'root';
$database = 'vaaga_elearn';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if roles table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'roles'");
    if ($stmt->rowCount() == 0) {
        $sql = "CREATE TABLE `roles` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `roles_name_guard_name_index` (`name`,`guard_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $pdo->exec($sql);
        echo "✓ Created roles table\n";
    } else {
        echo "✓ Roles table already exists\n";
    }
    
    // Check if model_has_roles table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'model_has_roles'");
    if ($stmt->rowCount() == 0) {
        $sql = "CREATE TABLE `model_has_roles` (
            `role_id` int(10) unsigned NOT NULL,
            `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `model_id` bigint(20) unsigned NOT NULL,
            PRIMARY KEY (`role_id`,`model_id`,`model_type`),
            KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
            CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $pdo->exec($sql);
        echo "✓ Created model_has_roles table\n";
    } else {
        echo "✓ model_has_roles table already exists\n";
    }
    
    // Check if role_has_permissions table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'role_has_permissions'");
    if ($stmt->rowCount() == 0) {
        // First check permissions table structure
        $permStmt = $pdo->query("DESCRIBE permissions");
        $permRow = $permStmt->fetch(PDO::FETCH_ASSOC);
        $permIdType = $permRow ? $permRow['Type'] : 'int(10) unsigned';
        
        $sql = "CREATE TABLE `role_has_permissions` (
            `permission_id` int(10) unsigned NOT NULL,
            `role_id` int(10) unsigned NOT NULL,
            PRIMARY KEY (`permission_id`,`role_id`),
            KEY `role_has_permissions_role_id_foreign` (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $pdo->exec($sql);
        
        // Add foreign keys separately if they don't exist
        try {
            $pdo->exec("ALTER TABLE `role_has_permissions` ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE");
        } catch (PDOException $e) {
            // Foreign key might already exist or permissions table structure differs
        }
        try {
            $pdo->exec("ALTER TABLE `role_has_permissions` ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE");
        } catch (PDOException $e) {
            // Foreign key might already exist
        }
        echo "✓ Created role_has_permissions table\n";
    } else {
        echo "✓ role_has_permissions table already exists\n";
    }
    
    echo "\n✓ All missing tables created successfully!\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

