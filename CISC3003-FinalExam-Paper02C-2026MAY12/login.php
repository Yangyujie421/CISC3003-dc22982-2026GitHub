<?php
session_start();
require_once __DIR__ . '/php/connect.php';

function h($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email) {
        $errors[] = 'A valid email is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!$errors) {
        $stmt = $conn->prepare('SELECT id, username, email, password, is_verified FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Incorrect email or password.';
        } elseif ((int)$user['is_verified'] !== 1) {
            $errors[] = 'Please activate your email before logging in.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header><h1>C.04: Login Page</h1></header>
<main>
    <section class="card">
        <h2>Login</h2>
        <?php if ($errors): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?= h($email) ?>">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="register.php">Create account</a> | <a href="request-reset.php">Forgot password?</a></p>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
