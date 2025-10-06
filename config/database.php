<?php

if (!function_exists('loadEnv')) {
    function loadEnv(string $path): array
    {
        if (!file_exists($path)) {
            // Log error in production instead of dying
            if (getenv('APP_ENV') === 'production') {
                error_log('Missing .env file at: ' . $path);
                return [];
            }
            die('Missing .env file at: ' . $path);
        }

        $env = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            [$key, $value] = array_map('trim', explode('=', $line, 2));
            $env[$key] = $value;
            putenv("$key=$value"); // Optional: make available via getenv()
        }
        return $env;
    }
}

if (!function_exists('getDatabaseConnection')) {
    function getDatabaseConnection(): PDO
    {
        // Load environment variables
        $env = loadEnv(__DIR__ . '/../.env');

        $config = [
            'host' => $env['DB_HOST'] ?? 'localhost',
            'dbname' => $env['DB_NAME'] ?? 'php_mvc_boilerplate',
            'user' => $env['DB_USER'] ?? 'root',
            'pass' => $env['DB_PASS'] ?? '',
            'charset' => $env['DB_CHARSET'] ?? 'utf8mb4',
            'env' => $env['APP_ENV'] ?? 'development',
        ];

        // Create a connection without specifying the database
        $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
        try {
            $pdo = new PDO($dsn, $config['user'], $config['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the database exists
            $result = $pdo->query("SHOW DATABASES LIKE '{$config['dbname']}'");
            if ($result->rowCount() === 0) {
                // Create the database
                $pdo->exec("CREATE DATABASE `{$config['dbname']}` CHARACTER SET {$config['charset']} COLLATE utf8mb4_unicode_ci");
            }

            // Connect to the database
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $pdo = new PDO($dsn, $config['user'], $config['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (PDOException $e) {
            // Log error instead of displaying in production
            if ($config['env'] === 'production') {
                error_log('Database connection failed: ' . $e->getMessage());
                die('Unable to connect to the database. Please try again later.');
            }
            die('Database connection failed: ' . $e->getMessage());
        }
    }
}

return getDatabaseConnection();