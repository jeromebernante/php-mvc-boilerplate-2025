<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;

class UserController
{
    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $user = (new User())->find($_SESSION['user_id']);
        if ($_POST) {
            (new User())->update($user['id'], $_POST);
            header('Location: /profile');
            exit;
        }
        return view('user/profile', ['title' => 'Profile', 'user' => $user]);
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
                $transaction = new Transaction();
                $transaction->create($wallet['id'], 'deposit', $amount, $_POST['description'] ?? '');
                (new Wallet())->updateBalance($wallet['id'], $amount, 'deposit');
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
                $transaction = new Transaction();
                $transaction->create($wallet['id'], 'withdraw', $amount, $_POST['description'] ?? '');
                (new Wallet())->updateBalance($wallet['id'], $amount, 'withdraw');
                header('Location: /profile');
                exit;
            }
        }
        return view('user/withdraw', ['title' => 'Withdraw', 'wallet' => $wallet]);
    }
}