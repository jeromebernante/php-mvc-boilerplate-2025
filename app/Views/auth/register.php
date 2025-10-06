<!-- app/Views/auth/register.php -->
<h1><?= $title ?? 'Register' ?></h1>
<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="/register">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" required><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br>
    <label for="phone">Phone (optional):</label><br>
    <input type="tel" id="phone" name="phone"><br>
    <label for="address">Address (optional):</label><br>
    <textarea id="address" name="address"></textarea><br>
    <input type="submit" value="Register">
</form>
<p>Already have an account? <a href="/login">Login</a></p>