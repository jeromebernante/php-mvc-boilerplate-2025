<?php

class CreateTransactionsTable
{
    public function up(PDO $pdo)
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS transactions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                wallet_id INT NOT NULL,
                type ENUM('deposit', 'withdraw') NOT NULL,
                amount DECIMAL(10, 2) NOT NULL,
                status ENUM('pending', 'completed') DEFAULT 'pending',
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE
            )
        ");
    }

    public function down(PDO $pdo)
    {
        $pdo->exec("DROP TABLE IF EXISTS transactions");
    }
}