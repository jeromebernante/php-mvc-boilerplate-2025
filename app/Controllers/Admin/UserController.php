<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController as BaseAdminController;
use App\Models\User;

class UserController extends BaseAdminController
{
    public function index()
    {
        $this->checkAdmin();
        $users = (new User())->all();
        return view('admin/users/index', ['title' => 'Manage Users', 'users' => $users]);
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $user = (new User())->find($id);
        if ($_POST) {
            (new User())->update($id, $_POST);
            header('Location: /admin/users');
            exit;
        }
        return view('admin/users/edit', ['title' => 'Edit User', 'user' => $user]);
    }

    public function delete($id)
    {
        $this->checkAdmin();
        (new User())->delete($id);
        header('Location: /admin/users');
        exit;
    }
}