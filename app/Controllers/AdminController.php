<?php

namespace App\Controllers;

use App\Models\User;

class AdminController
{
    protected function checkAdmin()
    {
        if (!isset($_SESSION['user_id']) || !(new User())->isAdmin($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function dashboard()
    {
        try {
            $users = (new User())->all();
            return view('admin/dashboard', [
                'title' => 'Admin Dashboard',
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return view('admin/dashboard', [
                'title' => 'Admin Dashboard',
                'users' => [],
                'error' => 'Failed to load users: ' . $e->getMessage()
            ]);
        }
    }
}