<?php
require_once __DIR__ . '/../../lib/functions.php';
require_once __DIR__ . '/../../lib/security.php';

// If already logged in redirect to admin index
$user = new Admin_User();
if ($user->is_logged()) {
    header('Location: ' . _SITE_URL . 'admin/');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    $token = $_POST['_csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Invalid form submission (CSRF token).';
    } else {
        $u = isset($_POST['username']) ? trim($_POST['username']) : '';
        $p = isset($_POST['password']) ? $_POST['password'] : '';
        if ($user->login($u, $p)) {
            header('Location: ' . _SITE_URL . 'admin/');
            exit;
        } else {
            $errors[] = 'Invalid credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Admin login</title></head>
<body>
<h2>Admin Login</h2>
<?php
if ($errors) {
    foreach ($errors as $e) {
        echo '<div style="color:red;">' . e($e) . '</div>';
    }
}
?>
<form method="post">
    <?php echo csrf_input(); ?>
    <label>Username<br/><input name="username" /></label><br/>
    <label>Password<br/><input name="password" type="password" /></label><br/>
    <button type="submit">Login</button>
</form>
</body>
</html>