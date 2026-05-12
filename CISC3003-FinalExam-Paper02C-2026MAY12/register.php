<?php
require_once __DIR__ . '/php/connect.php';
require_once __DIR__ . '/php/send_mail.php';

function h($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }

$errors = [];
$success = false;
$activation_url = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_ ]{3,100}$/', $username)) {
        $errors[] = 'Username must be 3-100 characters and use letters, numbers, spaces, or underscores only.';
    }
    if (!$email) {
        $errors[] = 'A valid email is required.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = 'This email is already registered.';
        }
        $check->close();
    }

    if (!$errors) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));

        $stmt = $conn->prepare('INSERT INTO users (username, email, password, is_verified, token) VALUES (?, ?, ?, 0, ?)');
        $stmt->bind_param('ssss', $username, $email, $password_hash, $token);

        if ($stmt->execute()) {
    $success = true;
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $activation_url = $scheme . '://' . $host . $path . '/activate.php?token=' . urlencode($token);

    $mail_status = '';
    $mail_body = "Hello $username,\n\n"
        . "Thank you for registering.\n\n"
        . "Please click this activation link to verify your account:\n"
        . $activation_url . "\n\n"
        . "CISC3003 Web Programming";

    send_cisc3003_email(
        $email,
        $username,
        'Activate your CISC3003 account',
        $mail_body,
        $mail_status
    );
    } else {
            $errors[] = 'Registration failed: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - Register</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
<header><h1>C.01 / C.02 / C.03 / C.05 / C.06: Signup Page</h1></header>
<main>
    <section class="card">
        <h2>Create Account</h2>
        <?php if ($success): ?>
            <div class="success">
                Account created successfully. Activation email has been sent. You may also use this local testing activation link:
                <br><a href="<?= h($activation_url) ?>"><?= h($activation_url) ?></a>
            </div>
            <p><a class="button" href="login.php">Go to Login</a></p>
        <?php else: ?>
            <?php if ($errors): ?>
                <div class="error"><ul><?php foreach ($errors as $error): ?><li><?= h($error) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>
            <form id="registerForm" action="register.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required maxlength="100" value="<?= h($username) ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required maxlength="120" data-check-email="true" value="<?= h($email) ?>">
                <div id="emailFeedback" class="small"></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6">

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">

                <button type="submit">Register</button>
            </form>
        <?php endif; ?>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
