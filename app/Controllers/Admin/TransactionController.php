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
        (new Transaction())->updateStatus($id, 'completed');
        header('Location: /admin/transactions');
        exit;
    }
}