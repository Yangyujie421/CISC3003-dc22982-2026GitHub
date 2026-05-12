<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario C - User System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <h1>Scenario C: Signup and Login System</h1>
    <p>C.01 - C.09 demonstration</p>
</header>
<main>
    <section class="card">
        <h2>Welcome to the User Service</h2>
        <p>This project demonstrates registration, server-side PHP validation, MySQL storage, login/logout, JavaScript validation, Ajax email checking, password reset, email activation, and a dashboard.</p>
        <p>
            <a class="button" href="register.php">Create Account</a>
            <a class="button secondary" href="login.php">Login</a>
        </p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p class="success">You are already logged in. <a href="dashboard.php">Open Dashboard</a></p>
        <?php endif; ?>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
