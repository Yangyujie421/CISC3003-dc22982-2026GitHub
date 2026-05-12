<?php
$config_file = __DIR__ . '/php/mail_config.php';
$config = file_exists($config_file) ? require $config_file : [];
$autoload = __DIR__ . '/vendor/autoload.php';
$checks = [
    'PHPMailer vendor/autoload.php exists' => file_exists($autoload) ? 'YES' : 'NO',
    'OpenSSL extension loaded' => extension_loaded('openssl') ? 'YES' : 'NO',
    'SMTP host' => $config['host'] ?? 'Missing',
    'SMTP username configured' => (($config['username'] ?? '') !== 'yourgmail@gmail.com') ? 'YES' : 'NO',
    'SMTP app password configured' => (($config['password'] ?? '') !== 'your_gmail_app_password') ? 'YES' : 'NO'
];
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Scenario B Debug</title><link rel="stylesheet" href="css/styles.css"></head>
<body>
<header><h1>Scenario B: PHPMailer Debug Page</h1></header>
<main><section class="card">
    <h2>Debug Checklist</h2>
    <table><tr><th>Check</th><th>Status</th></tr>
    <?php foreach ($checks as $check => $status): ?>
        <tr><td><?= htmlspecialchars($check, ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></td></tr>
    <?php endforeach; ?>
    </table>
    <p class="note">If PHPMailer is missing, open Command Prompt in this project folder and run: <code>composer require phpmailer/phpmailer</code></p>
    <p><a class="button" href="index.php">Back</a></p>
</section></main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body></html>
