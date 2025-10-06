<!-- app/Views/admin/dashboard.php -->
<h1><?= $title ?? 'Admin Dashboard' ?></h1>
<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<ul>
    <?php foreach ($users ?? [] as $user): ?>
        <li><?= htmlspecialchars($user['name'] ?? 'Unknown') ?></li>
    <?php endforeach; ?>
</ul>