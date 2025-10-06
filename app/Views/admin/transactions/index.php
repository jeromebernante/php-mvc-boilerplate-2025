<?php require __DIR__ . '/../_nav.php'; ?>
<h1><?= $title ?? 'Transactions' ?></h1>
<?php if (empty($transactions)): ?>
    <p>No transactions found.</p>
<?php else: ?>
    <table>
        <tr><th>ID</th><th>User ID</th><th>Type</th><th>Amount</th><th>Description</th><th>Date</th><th>Actions</th></tr>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= $t['user_id'] ?? '' ?></td>
                <td><?= htmlspecialchars($t['type']) ?></td>
                <td><?= number_format($t['amount'], 2) ?></td>
                <td><?= htmlspecialchars($t['description']) ?></td>
                <td><?= $t['created_at'] ?></td>
                <td>
                    <?php if (($t['status'] ?? '') !== 'completed'): ?>
                        <a href="/admin/transactions/<?= $t['id'] ?>/approve">Approve</a>
                    <?php else: ?>
                        Completed
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
