<?php

namespace App\Http;

class Router
{
    protected $routes = [];
    protected $prefix = '';

    public function group(array $attributes, callable $callback)
    {
        $previousPrefix = $this->prefix;
        $this->prefix .= ($attributes['prefix'] ?? '') . '/';
        $callback($this);
        $this->prefix = $previousPrefix;
    }

    public function add(string $method, string $uri, $action)
    {
        $uri = trim($this->prefix . $uri, '/');
        $this->routes[$method][$uri] = $action;
    }

    public function get(string $uri, $action)
    {
        $this->add('GET', $uri, $action);
    }

    public function post(string $uri, $action)
    {
        $this->add('POST', $uri, $action);
    }

    // Add more methods like put, delete if needed

    public function dispatch()
    {
        // Start session only if it hasn't been started already to avoid warnings
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Centralized protection for admin routes: block access if user is not admin
        if (strpos($requestUri, 'admin') === 0) {
            // Use session information and helper if available
            if (!isset($_SESSION['user_id'])) {
                http_response_code(403);
                echo '403 Forbidden - admin access only (not authenticated)';
                return;
            }
            // Attempt to check role via User model to avoid requiring helper here
            $dbUserCheck = false;
            try {
                $userModel = new \App\Models\User();
                $dbUserCheck = $userModel->isAdmin($_SESSION['user_id']);
            } catch (\Throwable $e) {
                $dbUserCheck = false;
            }
            if (!$dbUserCheck) {
                http_response_code(403);
                echo '403 Forbidden - admin access only';
                return;
            }
        }

        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $uri => $action) {
                $uriPattern = preg_replace('/\{([^\/]+)\}/', '([^/]+)', $uri);
                if (preg_match("#^$uriPattern$#", $requestUri, $matches)) {
                    array_shift($matches); // Remove full match
                    if (is_callable($action)) {
                        return call_user_func_array($action, $matches);
                    } elseif (is_string($action)) {
                        [$controller, $method] = explode('@', $action);
                        $controller = "App\\Controllers\\" . $controller;
                        $instance = new $controller();
                        return call_user_func_array([$instance, $method], $matches);
                    }
                }
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
