<?php

require __DIR__ . '/../vendor/autoload.php';

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

// Basic auth check helper (used in views if needed)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Load routes
$router = require __DIR__ . '/../routes/web.php';

// Dispatch the request
echo $router->dispatch();