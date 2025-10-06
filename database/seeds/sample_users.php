<?php

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

require __DIR__ . '/../../vendor/autoload.php';

$db = require __DIR__ . '/../../config/database.php';

// Load environment variables
$env = loadEnv(__DIR__ . '/../../.env');
$adminEmail = $env['ADMIN_EMAIL'] ?? 'admin@example.com';
$adminPass = password_hash($env['ADMIN_PASSWORD'] ?? 'admin123', PASSWORD_DEFAULT);

try {
    // Insert admin user
    $stmt = $db->prepare("
        INSERT IGNORE INTO users (name, email, password, role) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute(['Admin User', $adminEmail, $adminPass, 'admin']);

    // Get the admin user ID
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminEmail]);
    $adminId = $stmt->fetchColumn();

    if ($adminId) {
        // Insert wallet for admin
        $stmt = $db->prepare("
            INSERT IGNORE INTO wallets (user_id, balance) 
            VALUES (?, ?)
        ");
        $stmt->execute([$adminId, 1000.00]);
    }

    echo "Sample admin and wallet seeded successfully!\n";
} catch (Exception $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
}