<h1><?= $title ?? 'Admin Profile' ?></h1>
<?php require __DIR__ . '/_nav.php'; ?>
<p>Name: <?= htmlspecialchars($user['name'] ?? '') ?></p>
<p>Email: <?= htmlspecialchars($user['email'] ?? '') ?></p>
<a href="/admin/dashboard">Back to Dashboard</a> | <a href="/logout">Logout</a>
