<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController as BaseAdminController;
use App\Models\Wallet;

class WalletController extends BaseAdminController
{
    public function index()
    {
        $this->checkAdmin();
        $wallets = (new Wallet())->all();
        return view('admin/wallets/index', ['title' => 'Manage Wallets', 'wallets' => $wallets]);
    }
}