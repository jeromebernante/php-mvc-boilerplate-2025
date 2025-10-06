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
        // Allow setting role when creating from admin panel; default to 'user'
        $role = $data['role'] ?? 'user';
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)');
        $success = $stmt->execute([$data['name'], $data['email'], $hashedPassword, $data['phone'] ?? null, $data['address'] ?? null, $role]);
        if ($success) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, array $data)
    {
        // If password provided, update it as well
        $params = [$data['name'], $data['phone'] ?? null, $data['address'] ?? null];
        $sql = 'UPDATE users SET name = ?, phone = ?, address = ?';

        if (!empty($data['password'])) {
            $sql .= ', password = ?';
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Allow role update when provided (admin editing)
        if (isset($data['role'])) {
            $sql .= ', role = ?';
            $params[] = $data['role'];
        }

        $sql .= ', updated_at = CURRENT_TIMESTAMP WHERE id = ?';
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
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