<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController as BaseAdminController;
use App\Models\Transaction;

class TransactionController extends BaseAdminController
{
    public function index()
    {
        $this->checkAdmin();
        $transactions = (new Transaction())->all();
        return view('admin/transactions/index', ['title' => 'Manage Transactions', 'transactions' => $transactions]);
    }

    public function approve($id)
    {
        $this->checkAdmin();
        $transactionModel = new Transaction();
        $tx = $transactionModel->find($id);
        if ($tx && ($tx['status'] ?? '') !== 'completed') {
            // Apply wallet balance change
            $walletId = $tx['wallet_id'];
            if ($tx['type'] === 'deposit') {
                (new \App\Models\Wallet())->updateBalance($walletId, $tx['amount'], 'deposit');
            } else {
                (new \App\Models\Wallet())->updateBalance($walletId, $tx['amount'], 'withdraw');
            }
            $transactionModel->updateStatus($id, 'completed');
            flash('success', 'Transaction approved and balance updated.');
        }
        header('Location: /admin/transactions');
        exit;
    }
}