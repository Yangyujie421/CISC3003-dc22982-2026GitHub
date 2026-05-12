<?php
session_start();
require_once __DIR__ . '/php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

function h($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }

$stmt = $conn->prepare('SELECT username, email, created_at FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<header>
    <h1>C.09: User Dashboard</h1>
    <p>Welcome, <?= h($user['username'] ?? $_SESSION['username']) ?></p>
</header>
<main>
    <section class="card">
        <h2>Account Information</h2>
        <table>
            <tr><th>Username</th><td><?= h($user['username'] ?? '') ?></td></tr>
            <tr><th>Email</th><td><?= h($user['email'] ?? '') ?></td></tr>
            <tr><th>Member Since</th><td><?= h($user['created_at'] ?? '') ?></td></tr>
        </table>
        <div class="service-grid">
            <div class="service-card"><h3>Profile Service</h3><p>View and manage user profile information.</p></div>
            <div class="service-card"><h3>Message Service</h3><p>Send and receive service messages.</p></div>
            <div class="service-card"><h3>Security Service</h3><p>Password hashing, login session, and account protection.</p></div>
            <div class="service-card"><h3>Support Service</h3><p>Contact support after logging in.</p></div>
        </div>
        <p><a class="button secondary" href="logout.php">Logout</a></p>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
