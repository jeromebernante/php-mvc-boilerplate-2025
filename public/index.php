<?php

require __DIR__ . '/../vendor/autoload.php';

// Start session early so helper functions can use it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include auth helpers (defines isLoggedIn(), currentUser(), isAdmin())
require_once __DIR__ . '/../app/Helpers/Auth.php';
// Include flash helper
require_once __DIR__ . '/../app/Helpers/Flash.php';

// Helper function to render views (like Laravel's view())
function view(string $view, array $data = []): string
{
    $viewPath = __DIR__ . '/../app/Views/' . str_replace('.', '/', $view) . '.php';
    if (!file_exists($viewPath)) {
        throw new \Exception("View $view not found");
    }

    extract($data);
    ob_start();
    require $viewPath;
    $content = ob_get_clean();

    // Wrap in layout (optional; mimic Laravel layouts)
    $layoutPath = __DIR__ . '/../app/Views/layouts/main.php';
    ob_start();
    require $layoutPath;
    return ob_get_clean();
}

// Load routes
$router = require __DIR__ . '/../routes/web.php';

// Dispatch the request
echo $router->dispatch();
