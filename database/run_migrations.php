<?php

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php'; // Uses existing PDO connection

// Create migrations table if not exists
$config->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration_name VARCHAR(255) NOT NULL,
        batch INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_migration (migration_name)
    )
");

// Get pending migrations
$migrationsDir = __DIR__ . '/migrations/';
$migrationFiles = glob($migrationsDir . '*.php');
sort($migrationFiles); // Sort by filename (001, 002, etc.)

$applied = $config->query("SELECT migration_name FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

foreach ($migrationFiles as $file) {
    $name = basename($file, '.php');
    if (!in_array($name, $applied)) {
        echo "Running migration: $name\n";
        require $file;
        // Remove numeric prefix (e.g., '001_') to get class name
        $className = preg_replace('/^\d+_/', '', basename($file, '.php'));
        $className = str_replace('_', '', $className); // Convert to camelCase
        $migration = new $className();
        $migration->up($config);
        $config->exec("INSERT INTO migrations (migration_name, batch) VALUES ('$name', 1)");
    }
}

echo "Migrations completed!\n";