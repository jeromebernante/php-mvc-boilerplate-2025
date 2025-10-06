<?php

namespace App\Models;

use PDO;
use PDOException;

class User
{
    protected $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../config/database.php';
    }

    public function all()
    {
        try {
            $stmt = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)');
        $success = $stmt->execute([$data['name'], $data['email'], $hashedPassword, $data['phone'] ?? null, $data['address'] ?? null]);
        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, array $data)
    {
        $stmt = $this->db->prepare('UPDATE users SET name = ?, phone = ?, address = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        return $stmt->execute([$data['name'], $data['phone'] ?? null, $data['address'] ?? null, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin($userId)
    {
        $stmt = $this->db->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() === 'admin';
    }
}