<?php

namespace App\Models;

use PDO;
use PDOException;

class Transaction
{
    protected $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../config/database.php';
    }

    public function create($walletId, $type, $amount, $description = '')
    {
        $stmt = $this->db->prepare('INSERT INTO transactions (wallet_id, type, amount, description) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$walletId, $type, $amount, $description]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare('UPDATE transactions SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public function all()
    {
        try {
            $stmt = $this->db->query('SELECT t.*, w.user_id FROM transactions t JOIN wallets w ON t.wallet_id = w.id ORDER BY t.created_at DESC');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM transactions WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}