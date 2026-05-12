<?php

function send_cisc3003_email($to_email, $to_name, $subject, $body, &$mail_status = '')
{
    $autoload = dirname(__DIR__) . '/vendor/autoload.php';

    if (!file_exists($autoload)) {
        $mail_status = 'PHPMailer is not installed.';
        return false;
    }

    require_once $autoload;

    $config_file = __DIR__ . '/mail_config.php';

    if (!file_exists($config_file)) {
        $mail_status = 'mail_config.php not found.';
        return false;
    }

    $config = require $config_file;

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['username'];
        $mail->Password = $config['password'];
        $mail->SMTPSecure = $config['secure'];
        $mail->Port = $config['port'];

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($to_email, $to_name);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

        $mail_status = 'Email sent successfully by PHPMailer.';
        return true;
    } catch (Exception $e) {
        $mail_status = 'PHPMailer error: ' . $mail->ErrorInfo;
        return false;
    }
}