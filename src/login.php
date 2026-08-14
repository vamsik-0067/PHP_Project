<?php
require_once 'config.php';

if (logged_in()) redirect('index.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare("SELECT id,name,email,password,role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $account = $stmt->fetch();

    if ($account && hash('sha256', $password) === $account['password']) {
        unset($account['password']);
        $_SESSION['user'] = $account;
        redirect('index.php');
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DevTrack Login</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-page">
<div class="auth-card">
    <div class="brand large"><span>◆</span> DevTrack</div>
    <h1>Welcome back</h1>
    <p class="muted">Sign in to manage your projects and tasks.</p>

    <?php if ($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>

    <form method="post">
        <label>Email</label>
        <input type="email" name="email" placeholder="you@example.com" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
        <button class="btn primary full">Sign In</button>
    </form>

    <div class="demo-box">
        <strong>Demo accounts</strong><br>
        Admin: admin@devtrack.local / admin123<br>
        Developer: vamsi@devtrack.local / developer123
    </div>
</div>
</body>
</html>
