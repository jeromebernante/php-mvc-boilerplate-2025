<?php require __DIR__ . '/../_nav.php'; ?>
<h1><?= $title ?? 'Admin Deposit' ?></h1>
<form method="POST" action="/admin/users/<?= $user['id'] ?>/deposit">
    <label>Amount</label><br>
    <input type="number" name="amount" step="0.01" min="0.01" required><br>
    <label>Description (optional)</label><br>
    <input type="text" name="description"><br>
    <input type="submit" value="Deposit">
</form>
<a href="/admin/users">Back to users</a>
