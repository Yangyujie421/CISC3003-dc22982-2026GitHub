<?php
require_once __DIR__ . '/php/connect.php';

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$errors = [];
$full_name = trim($_POST['full_name'] ?? '');
$email_raw = trim($_POST['email'] ?? '');
$email = filter_var($email_raw, FILTER_VALIDATE_EMAIL);
$age = filter_var($_POST['age'] ?? null, FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 16, 'max_range' => 100]
]);
$gender = $_POST['gender'] ?? '';
$course = $_POST['course'] ?? '';
$hobbies = $_POST['hobbies'] ?? [];
$message = trim($_POST['message'] ?? '');
$agree_terms = isset($_POST['agree_terms']) ? 1 : 0;

$allowed_genders = ['Male', 'Female', 'Other'];
$allowed_courses = ['CISC3003', 'CISC1001', 'CISC2002'];
$allowed_hobbies = ['Coding', 'Reading', 'Sports', 'Music'];

if ($full_name === '' || strlen($full_name) > 100) {
    $errors[] = 'Full name is required and must be less than 100 characters.';
}
if (!$email) {
    $errors[] = 'A valid email address is required.';
}
if ($age === false) {
    $errors[] = 'Age must be between 16 and 100.';
}
if (!in_array($gender, $allowed_genders, true)) {
    $errors[] = 'Please select a valid gender.';
}
if (!in_array($course, $allowed_courses, true)) {
    $errors[] = 'Please select a valid course.';
}
$clean_hobbies = array_values(array_intersect($allowed_hobbies, (array) $hobbies));
if (count($clean_hobbies) === 0) {
    $errors[] = 'Please select at least one hobby.';
}
if ($message === '') {
    $errors[] = 'Comments cannot be empty.';
}
if ($agree_terms !== 1) {
    $errors[] = 'You must confirm the information.';
}

$hobby_text = implode(', ', $clean_hobbies);
$success = false;

if (!$errors) {
    $stmt = $conn->prepare('INSERT INTO students (full_name, email, age, gender, course, hobbies, message, agree_terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    if (!$stmt) {
        $errors[] = 'Prepare failed: ' . $conn->error;
    } else {
        $stmt->bind_param('ssissssi', $full_name, $email, $age, $gender, $course, $hobby_text, $message, $agree_terms);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = 'Database insert failed: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario A - Form Result</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <h1>Scenario A: PHP Form Processing Result</h1>
</header>
<main>
    <section class="card">
        <?php if ($success): ?>
            <div class="success">The form data was validated and inserted into the MySQL database successfully using a prepared statement.</div>
            <h2>Submitted Data</h2>
            <table>
                <tr><th>Field</th><th>Value</th></tr>
                <tr><td>Name</td><td><?= h($full_name) ?></td></tr>
                <tr><td>Email</td><td><?= h($email) ?></td></tr>
                <tr><td>Age</td><td><?= h($age) ?></td></tr>
                <tr><td>Gender</td><td><?= h($gender) ?></td></tr>
                <tr><td>Course</td><td><?= h($course) ?></td></tr>
                <tr><td>Hobbies</td><td><?= h($hobby_text) ?></td></tr>
                <tr><td>Comments</td><td><?= h($message) ?></td></tr>
            </table>
        <?php else: ?>
            <div class="error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <p><a class="button secondary" href="index.php">Back to Form</a></p>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
