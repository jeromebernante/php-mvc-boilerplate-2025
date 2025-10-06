<?php

use App\Http\Router;

$router = new Router();

// Public routes
$router->get('/', function() {
    return view('welcome', ['title' => 'Welcome to My App']);
});

// Auth routes
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@register');
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@login');
$router->get('logout', 'AuthController@logout');

// User routes
$router->get('profile', 'UserController@profile');
$router->post('profile', 'UserController@profile');
$router->get('deposit', 'UserController@deposit');
$router->post('deposit', 'UserController@deposit');
$router->get('withdraw', 'UserController@withdraw');
$router->post('withdraw', 'UserController@withdraw');

// Admin routes
$router->group(['prefix' => 'admin'], function(Router $router) {
    $router->get('dashboard', 'AdminController@dashboard');
    $router->get('users', 'Admin\\UserController@index');
    $router->get('users/{id}/edit', 'Admin\\UserController@edit');
    $router->post('users/{id}/edit', 'Admin\\UserController@edit');
    $router->get('users/{id}/delete', 'Admin\\UserController@delete');
    $router->get('wallets', 'Admin\\WalletController@index');
    $router->get('transactions', 'Admin\\TransactionController@index');
    $router->get('transactions/{id}/approve', 'Admin\\TransactionController@approve');
});

return $router;