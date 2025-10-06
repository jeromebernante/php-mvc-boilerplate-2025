<?php

class CreateWalletsTable
{
    public function up(PDO $pdo)
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS wallets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                balance DECIMAL(10, 2) DEFAULT 0.00,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
    }

    public function down(PDO $pdo)
    {
        $pdo->exec("DROP TABLE IF EXISTS wallets");
    }
}