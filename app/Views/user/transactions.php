<h1><?= $title ?? 'Transactions' ?></h1>
<?php if (!empty($wallet)): ?>
    <p>Balance: <?= number_format($wallet['balance'] ?? 0, 2) ?></p>
<?php endif; ?>
<?php if (empty($transactions)): ?>
    <p>No transactions found.</p>
<?php else: ?>
    <table>
        <tr><th>ID</th><th>Type</th><th>Amount</th><th>Status</th><th>Description</th><th>Date</th></tr>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['type']) ?></td>
                <td><?= number_format($t['amount'], 2) ?></td>
                <td>
                    <?php if (($t['status'] ?? '') === 'pending'): ?>
                        <span style="color: orange; font-weight: bold">Pending</span>
                    <?php else: ?>
                        <span style="color: green; font-weight: bold">Completed</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($t['description']) ?></td>
                <td><?= $t['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<a href="/profile">Back to profile</a>
