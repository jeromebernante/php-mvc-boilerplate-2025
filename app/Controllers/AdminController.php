<?php

namespace App\Controllers;

use App\Models\User;

class AdminController
{
    protected function checkAdmin()
    {
        if (!isset($_SESSION['user_id']) || !(new User())->isAdmin($_SESSION['user_id'])) {
            http_response_code(403);
            echo '403 Forbidden - admin access only';
            exit;
        }
    }

    public function dashboard()
    {
        // Ensure only admins can access the dashboard
        $this->checkAdmin();

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

    public function profile()
    {
        $this->checkAdmin();
        $user = (new User())->find($_SESSION['user_id']);
        return view('admin/profile', ['title' => 'Admin Profile', 'user' => $user]);
    }
}