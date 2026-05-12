<?php
require_once __DIR__ . '/php/connect.php';
function h($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
$message = '';
$class = 'error';
$token = $_GET['token'] ?? '';

if ($token !== '') {
    $stmt = $conn->prepare('UPDATE users SET is_verified = 1, token = NULL WHERE token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $message = 'Email account activated successfully. You may now log in.';
        $class = 'success';
    } else {
        $message = 'Invalid or already used activation token.';
    }
    $stmt->close();
} else {
    $message = 'Activation token is missing.';
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Scenario C - Activate Account</title><link rel="stylesheet" href="css/styles.css"></head>
<body><header><h1>C.08: Email Account Activation</h1></header>
<main><section class="card"><div class="<?= h($class) ?>"><?= h($message) ?></div><p><a class="button" href="login.php">Login</a></p></section></main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer></body></html>
