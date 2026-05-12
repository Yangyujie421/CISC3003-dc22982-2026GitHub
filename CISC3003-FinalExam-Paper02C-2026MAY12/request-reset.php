<?php
require_once __DIR__ . '/php/connect.php';

$mail_status = '';
$message = '';
$reset_url = '';

if (file_exists(__DIR__ . '/php/send_mail.php')) {
    require_once __DIR__ . '/php/send_mail.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $message = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            $token = bin2hex(random_bytes(32));

            // Important: use MySQL NOW() to avoid PHP/MySQL time mismatch
            $update = $conn->prepare(
                "UPDATE users 
                 SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                 WHERE id = ?"
            );

            $update->bind_param("si", $token, $user['id']);
            $update->execute();

            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

            $reset_url = $scheme . '://' . $host . $path . '/reset-password.php?token=' . urlencode($token);

            $subject = 'Reset your CISC3003 password';

            $body = "Hello " . $user['username'] . ",\n\n"
                . "You requested a password reset.\n\n"
                . "Please click the link below to reset your password:\n"
                . $reset_url . "\n\n"
                . "This link will expire in 1 hour.\n\n"
                . "CISC3003 Web Programming";

            if (function_exists('send_cisc3003_email')) {
                send_cisc3003_email(
                    $user['email'],
                    $user['username'],
                    $subject,
                    $body,
                    $mail_status
                );

                $message = 'Password reset email status: ' . $mail_status;
            } else {
                $message = 'Password reset link generated for local testing.';
            }
        } else {
            $message = 'If this email exists, a password reset link will be generated.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Password Reset</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>C.07: Request Password Reset</h1>
</header>

<main class="container">
    <h2>Request Password Reset</h2>

    <?php if (!empty($message)): ?>
        <div class="alert success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($reset_url)): ?>
        <div class="alert">
            <strong>Local testing reset link:</strong><br>
            <a href="<?php echo htmlspecialchars($reset_url); ?>">
                <?php echo htmlspecialchars($reset_url); ?>
            </a>
        </div>
    <?php endif; ?>

    <form method="POST" action="request-reset.php">
        <label>Registered Email</label>
        <input type="email" name="email" required>

        <button type="submit">Generate Reset Link</button>
    </form>

    <p>
        <a href="login.php">Back to Login</a>
    </p>
</main>

<footer>
    CISC3003 Web Programming: yangyujie + DC229823 + 2026
</footer>

</body>
</html>