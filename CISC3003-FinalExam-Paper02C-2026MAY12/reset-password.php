<?php
require_once __DIR__ . '/php/connect.php';

$message = '';
$error = '';
$show_form = false;

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$token = trim($token);

if (empty($token)) {
    $error = 'Invalid or expired reset token.';
} else {
    // Important: use MySQL NOW() to check expiry
    $stmt = $conn->prepare(
        "SELECT id, username, email 
         FROM users 
         WHERE reset_token = ? 
         AND reset_expires IS NOT NULL 
         AND reset_expires > NOW()
         LIMIT 1"
    );

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows !== 1) {
        $error = 'Invalid or expired reset token.';
    } else {
        $user = $result->fetch_assoc();
        $show_form = true;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $update = $conn->prepare(
                    "UPDATE users 
                     SET password = ?, reset_token = NULL, reset_expires = NULL 
                     WHERE id = ?"
                );

                $update->bind_param("si", $hashed_password, $user['id']);

                if ($update->execute()) {
                    $message = 'Password reset successfully. You can now login.';
                    $show_form = false;
                } else {
                    $error = 'Password reset failed. Please try again.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>C.07: Reset Password</h1>
</header>

<main class="container">

    <?php if (!empty($message)): ?>
        <div class="alert success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert error">
            <ul>
                <li><?php echo htmlspecialchars($error); ?></li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($show_form): ?>
        <h2>Create New Password</h2>

        <form method="POST" action="reset-password.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <label>New Password</label>
            <input type="password" name="password" required minlength="6">

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required minlength="6">

            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>

    <p>
        <a href="login.php">Back to Login</a>
    </p>

</main>

<footer>
    CISC3003 Web Programming: yangyujie + DC229823 + 2026
</footer>

</body>
</html>