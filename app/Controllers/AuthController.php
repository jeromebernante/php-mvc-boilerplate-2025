<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Wallet;

class AuthController
{
    public function register()
    {
        // If already logged in, redirect away from register page
        if (isLoggedIn()) {
            if (isAdmin()) {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /profile');
            }
            exit;
        }

        if ($_POST) {
            $user = new User();
            $userId = $user->create($_POST);
            if ($userId) {
                // Create wallet for the new user
                $wallet = new Wallet();
                $wallet->create($userId, 0.00);
                
                $_SESSION['user_id'] = $userId;
                header('Location: /profile');
                exit;
            }
            return view('auth/register', ['title' => 'Register', 'error' => 'Registration failed']);
        }
        return view('auth/register', ['title' => 'Register']);
    }

    public function login()
    {
        // If already logged in, don't show login page
        if (isLoggedIn()) {
            if (isAdmin()) {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /profile');
            }
            exit;
        }

        if ($_POST) {
            $user = (new User())->findByEmail($_POST['email']);
            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: /profile');
                exit;
            }
            return view('auth/login', ['title' => 'Login', 'error' => 'Invalid email or password']);
        }
        return view('auth/login', ['title' => 'Login']);
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}