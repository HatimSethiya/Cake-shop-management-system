<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_admin()) redirect('dashboard.php');

$pageTitle = 'Admin Login | Sweet Crumbs';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect('dashboard.php');
        }

        $errors[] = 'Invalid username or password.';
    }
}

require __DIR__ . '/../includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">STAFF AREA</span><h1>Admin <em>login.</em></h1><p>Sign in to manage cakes and customer orders.</p></div></section>
<section class="section"><div class="container order-wrap"><form class="form-card" method="post" action="login.php">
    <h2>Welcome back</h2>
    <?php if ($errors): ?><div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label for="username">Username</label><input id="username" name="username" required autocomplete="username">
    <label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password">
    <button class="btn btn-primary btn-full" type="submit">Sign in <span>→</span></button>
    <p class="form-footnote">Demo login: admin / admin123</p>
</form></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
