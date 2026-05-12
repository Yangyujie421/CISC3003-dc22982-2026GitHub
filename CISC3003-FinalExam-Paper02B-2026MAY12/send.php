<?php
session_start();
require_once __DIR__ . '/php/connect.php';

function clean_text($value) {
    return trim(str_replace(["", "
"], ' ', $value));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = clean_text($_POST['name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = clean_text($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if (!$email) $errors[] = 'A valid email is required.';
if ($subject === '') $errors[] = 'Subject is required.';
if ($message === '') $errors[] = 'Message is required.';

$mail_status = 'Not sent yet';

if (!$errors) {
    $autoload = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($autoload)) {
        $mail_status = 'PHPMailer is not installed. Run: composer require phpmailer/phpmailer';
    } else {
        require $autoload;
        $config = require __DIR__ . '/php/mail_config.php';

        if ($config['username'] === 'yourgmail@gmail.com' || $config['password'] === 'your_gmail_app_password') {
            $mail_status = 'Email not sent: please configure php/mail_config.php with your Gmail address and app password.';
        } else {
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
                $mail->addAddress($config['to_email'], $config['to_name']);
                $mail->addReplyTo($email, $name);

                $mail->isHTML(false);
                $mail->Subject = '[CISC3003 Contact Form] ' . $subject;
                $mail->Body = "Name: $name
Email: $email

Message:
$message";

                $mail->send();
                $mail_status = 'Email sent successfully by PHPMailer.';
            } catch (Exception $e) {
                $mail_status = 'PHPMailer error: ' . $mail->ErrorInfo;
            }
        }
    }

    $stmt = $conn->prepare('INSERT INTO contact_messages (sender_name, sender_email, subject, message, mail_status) VALUES (?, ?, ?, ?, ?)');
    if ($stmt) {
        $stmt->bind_param('sssss', $name, $email, $subject, $message, $mail_status);
        $stmt->execute();
        $stmt->close();
    }
}

$_SESSION['flash'] = [
    'errors' => $errors,
    'mail_status' => $mail_status
];

header('Location: success.php');
exit;
