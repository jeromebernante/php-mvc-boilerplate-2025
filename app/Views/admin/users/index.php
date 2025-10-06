<?php require __DIR__ . '/../_nav.php'; ?>
<h1>Users</h1>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Balance</th><th>Actions</th></tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= number_format($user['balance'] ?? 0, 2) ?></td>
            <td>
                <a href="/admin/users/<?= $user['id'] ?>/edit">Edit</a> |
                <a href="/admin/users/<?= $user['id'] ?>/delete">Delete</a> |
                <a href="/admin/users/<?= $user['id'] ?>/deposit">Deposit</a> |
                <a href="/admin/users/<?= $user['id'] ?>/withdraw">Withdraw</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>