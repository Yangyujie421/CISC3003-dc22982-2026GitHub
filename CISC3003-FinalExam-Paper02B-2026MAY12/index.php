<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scenario B - Contact Form</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>
<body>
<header>
    <h1>Scenario B: Contact Form with PHPMailer</h1>
    <p>B.01 - B.05 demonstration</p>
</header>
<main>
    <section class="card">
        <h2>Contact Us</h2>
        <p class="small">This page demonstrates a contact form with client-side validation, PHPMailer SMTP sending, debugging support, and the Post/Redirect/Get pattern.</p>
        <form id="contactForm" action="send.php" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required maxlength="100">

            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required maxlength="120">

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required maxlength="150">

            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Send Message</button>
        </form>
        <p><a href="debug.php">Open PHPMailer Debug Page</a></p>
    </section>
</main>
<footer>CISC3003 Web Programming: yangyujie + DC229823 + 2026</footer>
</body>
</html>
