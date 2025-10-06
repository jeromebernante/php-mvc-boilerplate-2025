<?php

// Simple auth helper functions. Keep these in the global namespace so views and controllers
// can call isLoggedIn(), currentUser(), and isAdmin() without importing namespaces.

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;

if (!function_exists('isLoggedIn')) {
    function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

if (!function_exists('currentUser')) {
    function currentUser(): ?array
    {
        if (!isLoggedIn()) {
            return null;
        }
        $userModel = new User();
        return $userModel->find($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        if (!isLoggedIn()) {
            return false;
        }
        $userModel = new User();
        return $userModel->isAdmin($_SESSION['user_id']);
    }
}

if (!function_exists('pending_count')) {
    function pending_count(): int
    {
        if (!isLoggedIn()) return 0;
        $db = require __DIR__ . '/../../config/database.php';
        $stmt = $db->prepare('SELECT COUNT(*) FROM transactions t JOIN wallets w ON t.wallet_id = w.id WHERE t.status = ? AND w.user_id = ?');
        $stmt->execute(['pending', $_SESSION['user_id']]);
        return (int) $stmt->fetchColumn();
    }
}
