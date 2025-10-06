<h1><?= $title ?? 'Edit User' ?></h1>
<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="/admin/users/<?= $user['id'] ?>/edit">
    <label>Name</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required><br>

    <label>Email (cannot change)</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled><br>

    <label>Password (leave blank to keep current)</label><br>
    <input type="password" name="password"><br>

    <label>Phone</label><br>
    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"><br>

    <label>Address</label><br>
    <textarea name="address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea><br>

    <label>Role</label><br>
    <select name="role">
        <option value="user" <?= ($user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br><br>

    <input type="submit" value="Save">
</form>
<a href="/admin/users">Back to users</a>
