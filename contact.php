<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us | Sweet Crumbs';
$activePage = 'contact';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please refresh the page and try again.';
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || mb_strlen($name) > 100) $errors[] = 'Please enter your name (up to 100 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) $errors[] = 'Please enter a valid email address.';
    if ($message === '' || mb_strlen($message) > 2000) $errors[] = 'Please enter a message (up to 2,000 characters).';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $message]);
        set_flash('success', 'Thanks for writing to us. Your message has been received!');
        redirect('contact.php');
    }
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/flash.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">WE'D LOVE TO HEAR FROM YOU</span><h1>Say <em>hello.</em></h1><p>Questions, cake ideas or a little chat—our inbox is open.</p></div></section>
<section class="section"><div class="container contact-grid"><div class="contact-details"><span class="eyebrow">GET IN TOUCH</span><h2>Let's talk about <em>cake.</em></h2><p>Have a question about an order? Need help choosing a flavour? Send us a message.</p><div class="contact-detail"><span>✉</span><div><small>EMAIL</small><strong>hello@sweetcrumbs.example</strong></div></div><div class="contact-detail"><span>◷</span><div><small>OPENING HOURS</small><strong>Monday – Saturday, 9 AM – 8 PM</strong></div></div><div class="contact-detail"><span>⌖</span><div><small>LOCATION</small><strong>Your neighbourhood, Pune</strong></div></div></div>
<form class="form-card" method="post" action="contact.php" novalidate>
    <h2>Send us a note</h2>
    <?php if ($errors): ?><div class="alert alert-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label for="name">Your name</label><input id="name" name="name" maxlength="100" required value="<?= e($_POST['name'] ?? '') ?>" placeholder="What should we call you?">
    <label for="email">Email address</label><input id="email" type="email" name="email" maxlength="150" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="you@example.com">
    <label for="message">Your message</label><textarea id="message" name="message" rows="5" maxlength="2000" required placeholder="Tell us what's on your mind..."><?= e($_POST['message'] ?? '') ?></textarea>
    <button class="btn btn-primary btn-full" type="submit">Send message <span>→</span></button>
</form></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
