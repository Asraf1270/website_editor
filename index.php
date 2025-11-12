<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: editor.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Load users from JSON
    $usersJson = file_get_contents('includes/users.json');
    $users = json_decode($usersJson, true);

    $foundUser = null;
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser && password_verify($password, $foundUser['password_hash'])) {
        $_SESSION['user_id'] = $foundUser['id'];
        header('Location: editor.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Local File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>