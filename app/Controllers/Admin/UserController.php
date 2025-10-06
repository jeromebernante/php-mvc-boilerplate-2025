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
        // Attach wallet balance to each user for display
        $walletModel = new \App\Models\Wallet();
        foreach ($users as &$u) {
            $w = $walletModel->findByUserId($u['id']);
            $u['balance'] = $w['balance'] ?? 0;
        }
        return view('admin/users/index', ['title' => 'Manage Users', 'users' => $users]);
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $user = (new User())->find($id);
        if ($_POST) {
            // Build a clean payload for update
            $payload = [];
            $payload['name'] = trim($_POST['name'] ?? $user['name']);
            $payload['phone'] = trim($_POST['phone'] ?? $user['phone']);
            $payload['address'] = trim($_POST['address'] ?? $user['address']);
            if (!empty($_POST['password'])) {
                $payload['password'] = $_POST['password'];
            }
            if (isset($_POST['role'])) {
                $payload['role'] = $_POST['role'] === 'admin' ? 'admin' : 'user';
            }
            (new User())->update($id, $payload);
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

    public function deposit($id)
    {
        $this->checkAdmin();
        $user = (new User())->find($id);
        $wallet = (new \App\Models\Wallet())->findByUserId($id);
        if ($_POST) {
            $amount = floatval($_POST['amount']);
            if ($amount > 0) {
                $transactionModel = new \App\Models\Transaction();
                // Create as completed so it immediately reflects
                $txId = $transactionModel->create($wallet['id'], 'deposit', $amount, $_POST['description'] ?? '', 'completed');
                if ($txId) {
                    // Already created as completed in DB, update wallet
                    (new \App\Models\Wallet())->updateBalance($wallet['id'], $amount, 'deposit');
                    flash('success', 'Deposit completed for user.');
                }
                header('Location: /admin/users');
                exit;
            }
        }
        return view('admin/users/deposit', ['title' => 'Deposit for ' . ($user['name'] ?? ''), 'user' => $user, 'wallet' => $wallet]);
    }

    public function withdraw($id)
    {
        $this->checkAdmin();
        $user = (new User())->find($id);
        $wallet = (new \App\Models\Wallet())->findByUserId($id);
        if ($_POST) {
            $amount = floatval($_POST['amount']);
            if ($amount > 0 && $amount <= $wallet['balance']) {
                $transactionModel = new \App\Models\Transaction();
                $txId = $transactionModel->create($wallet['id'], 'withdraw', $amount, $_POST['description'] ?? '', 'completed');
                if ($txId) {
                    (new \App\Models\Wallet())->updateBalance($wallet['id'], $amount, 'withdraw');
                    flash('success', 'Withdraw completed for user.');
                }
                header('Location: /admin/users');
                exit;
            }
        }
        return view('admin/users/withdraw', ['title' => 'Withdraw for ' . ($user['name'] ?? ''), 'user' => $user, 'wallet' => $wallet]);
    }
}