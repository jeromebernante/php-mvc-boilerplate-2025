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
        // allow specifying status (default pending)
        $status = 'pending';
        // If caller provided a status as 5th argument (deprecated) handle it, otherwise keep default
        $args = func_get_args();
        if (isset($args[4]) && in_array($args[4], ['pending', 'completed'])) {
            $status = $args[4];
        }

        $stmt = $this->db->prepare('INSERT INTO transactions (wallet_id, type, amount, description, status) VALUES (?, ?, ?, ?, ?)');
        $success = $stmt->execute([$walletId, $type, $amount, $description, $status]);
        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
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