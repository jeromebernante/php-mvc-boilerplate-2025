<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'App' ?></title>
</head>
<body>
    <header>
        <nav>
            <a href="/">Home</a>
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    | <a href="/admin/dashboard">Admin Dashboard</a> | <a href="/admin/profile">Admin Profile</a>
                <?php else: ?>
                    | <a href="/profile">Profile</a> | <a href="/transactions">Transactions</a><?php $pc = pending_count(); if ($pc > 0): ?> <strong style="color: orange">(<?= $pc ?>)</strong><?php endif; ?>
                <?php endif; ?>
                | <a href="/logout">Logout</a>
            <?php else: ?>
                | <a href="/login">Login</a> | <a href="/register">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php if ($msg = flash('success')): ?>
            <div class="flash flash-success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
            <div class="flash flash-error"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </main>
    <footer>Footer</footer>
</body>
</html>