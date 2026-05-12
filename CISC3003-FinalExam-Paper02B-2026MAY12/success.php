<?php
session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B - Contact Result</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header><h1>Scenario B: Contact Form Result</h1></header>
<main>
    <section class="card">
        <?php if (!$flash): ?>
            <div class="note">No form result is available. Please submit the contact form first.</div>
        <?php elseif (!empty($flash['errors'])): ?>
            <div class="error">
                <strong>Please fix these errors:</strong>
                <ul>
                <?php foreach ($flash['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="success">Your form was processed using the Post/Redirect/Get pattern.</div>
            <p><strong>Mail status:</strong> <?= htmlspecialchars($flash['mail_status'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <p><a class="button" href="index.php">Back to Contact Form</a></p>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
