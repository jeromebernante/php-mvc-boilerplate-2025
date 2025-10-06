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
        session_start(); // Add this line

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

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
