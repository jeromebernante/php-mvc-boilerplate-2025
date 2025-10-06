<h1>Profile</h1>
<p>Balance: <?= $wallet['balance'] ?? 0 ?></p>
<p>Role: <?= htmlspecialchars($user['role'] ?? 'user') ?></p>
<form method="POST">
    <input type="text" name="name" value="<?= $user['name'] ?>" required>
    <input type="tel" name="phone" value="<?= $user['phone'] ?>">
    <textarea name="address"><?= $user['address'] ?></textarea>
    <input type="submit" value="Update">
</form>
<a href="/deposit">Deposit</a> | <a href="/withdraw">Withdraw</a> | <a href="/transactions">Transactions</a> | <a href="/logout">Logout</a>