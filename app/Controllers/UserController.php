<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;

class UserController
{
    public function profile()
    {
        if (!isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        // If the current user is an admin, redirect them to the admin profile
        if (isAdmin()) {
            header('Location: /admin/profile');
            exit;
        }

        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);

        // Load wallet for current user
        $walletModel = new Wallet();
        $wallet = $walletModel->findByUserId($user['id']);

        if ($_POST) {
            (new User())->update($user['id'], $_POST);
            header('Location: /profile');
            exit;
        }

        return view('user/profile', ['title' => 'Profile', 'user' => $user, 'wallet' => $wallet]);
    }

    public function deposit()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $wallet = (new Wallet())->findByUserId($_SESSION['user_id']);
        if ($_POST) {
            $amount = floatval($_POST['amount']);
            if ($amount > 0) {
                // create pending transaction; admin must approve to affect balance
                $transaction = new Transaction();
                $transaction->create($wallet['id'], 'deposit', $amount, $_POST['description'] ?? '');
                flash('success', 'Deposit request submitted and is pending approval.');
                header('Location: /profile');
                exit;
            }
        }
        return view('user/deposit', ['title' => 'Deposit', 'wallet' => $wallet]);
    }

    public function withdraw()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $wallet = (new Wallet())->findByUserId($_SESSION['user_id']);
        if ($_POST) {
            $amount = floatval($_POST['amount']);
            if ($amount > 0 && $amount <= $wallet['balance']) {
                // create pending withdraw transaction; admin must approve to affect balance
                $transaction = new Transaction();
                $transaction->create($wallet['id'], 'withdraw', $amount, $_POST['description'] ?? '');
                flash('success', 'Withdraw request submitted and is pending approval.');
                header('Location: /profile');
                exit;
            }
        }
        return view('user/withdraw', ['title' => 'Withdraw', 'wallet' => $wallet]);
    }

    public function transactions()
    {
        if (!isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        $wallet = (new \App\Models\Wallet())->findByUserId($_SESSION['user_id']);
        $transactions = [];
        if ($wallet) {
            $stmt = (require __DIR__ . '/../../config/database.php')->prepare('SELECT * FROM transactions WHERE wallet_id = ? ORDER BY created_at DESC');
            $stmt->execute([$wallet['id']]);
            $transactions = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return view('user/transactions', ['title' => 'My Transactions', 'transactions' => $transactions, 'wallet' => $wallet]);
    }
}