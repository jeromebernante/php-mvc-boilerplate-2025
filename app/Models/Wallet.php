<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Wallet
{
    protected $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../config/database.php';
    }

    public function create($userId, $balance = 0.00)
    {
        $stmt = $this->db->prepare('INSERT INTO wallets (user_id, balance) VALUES (?, ?)');
        $success = $stmt->execute([$userId, $balance]);
        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM wallets WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateBalance($walletId, $amount, $type)
    {
        try {
            $this->db->beginTransaction();
            if ($type === 'deposit') {
                $stmt = $this->db->prepare('UPDATE wallets SET balance = balance + ? WHERE id = ?');
                $stmt->execute([$amount, $walletId]);
            } else {
                $stmt = $this->db->prepare('UPDATE wallets SET balance = balance - ? WHERE id = ? AND balance >= ?');
                $stmt->execute([$amount, $walletId, $amount]);
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Insufficient balance');
                }
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function all()
    {
        try {
            $stmt = $this->db->prepare('SELECT w.*, u.name FROM wallets w JOIN users u ON w.user_id = u.id ORDER BY w.created_at DESC');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}