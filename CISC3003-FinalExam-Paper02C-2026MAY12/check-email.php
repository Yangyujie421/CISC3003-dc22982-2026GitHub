<?php
header('Content-Type: application/json');
require_once __DIR__ . '/php/connect.php';

$email = filter_var(trim($_GET['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['available' => false, 'message' => 'Invalid email format.']);
    exit;
}

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['available' => false, 'message' => 'This email is already registered.']);
} else {
    echo json_encode(['available' => true, 'message' => 'This email is available.']);
}
$stmt->close();
