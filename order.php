<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Order a Cake | Sweet Crumbs';
$activePage = '';
$errors = [];
$selectedCakeId = filter_input(INPUT_GET, 'cake_id', FILTER_VALIDATE_INT) ?: 0;
$selectedCakeId = filter_input(INPUT_POST, 'cake_id', FILTER_VALIDATE_INT) ?: $selectedCakeId;
$cakes = $pdo->query('SELECT id, name, price FROM cakes WHERE is_available = 1 ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please refresh the page and try again.';
    }

    $customerName = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $deliveryDate = trim($_POST['delivery_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    $cakeStmt = $pdo->prepare('SELECT id FROM cakes WHERE id = ? AND is_available = 1');
    $cakeStmt->execute([$selectedCakeId]);

    if (!$cakeStmt->fetch()) $errors[] = 'Please choose an available cake.';
    if ($customerName === '' || mb_strlen($customerName) > 100) $errors[] = 'Please enter your name (up to 100 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) $errors[] = 'Please enter a valid email address.';
    if (!preg_match('/^[0-9+()\-\s]{7,20}$/', $phone)) $errors[] = 'Please enter a valid phone number.';
    if (!$quantity || $quantity < 1 || $quantity > 20) $errors[] = 'Quantity must be between 1 and 20.';
    $date = DateTime::createFromFormat('Y-m-d', $deliveryDate);
    $dateErrors = DateTime::getLastErrors();
    if (!$date || ($dateErrors && ($dateErrors['warning_count'] || $dateErrors['error_count'])) || $date->format('Y-m-d') !== $deliveryDate || $deliveryDate < date('Y-m-d')) {
        $errors[] = 'Please choose today or a future delivery date.';
    }
    if (mb_strlen($notes) > 1000) $errors[] = 'Special instructions must be 1,000 characters or fewer.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO orders (cake_id, customer_name, email, phone, quantity, delivery_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$selectedCakeId, $customerName, $email, $phone, $quantity, $deliveryDate, $notes]);
        set_flash('success', 'Your cake request has been placed! We will contact you to confirm the order.');
        redirect('order.php');
    }
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/flash.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">SOMETHING SWEET IS ON THE WAY</span><h1>Order your <em>cake.</em></h1><p>Share a few details and we'll get your cake request started.</p></div></section>
<section class="section"><div class="container order-wrap"><form class="form-card order-form" method="post" action="order.php" novalidate>
    <h2>Tell us what you're craving</h2><p class="form-intro">Fields marked with * are required.</p>
    <?php if ($errors): ?><div class="alert alert-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label for="cake_id">Choose your cake *</label><select id="cake_id" name="cake_id" required><option value="">Select a cake</option><?php foreach ($cakes as $cake): ?><option value="<?= (int)$cake['id'] ?>" <?= $selectedCakeId === (int)$cake['id'] ? 'selected' : '' ?>><?= e($cake['name']) ?> — ₹<?= number_format((float)$cake['price'], 2) ?></option><?php endforeach; ?></select>
    <div class="form-row"><div><label for="customer_name">Your name *</label><input id="customer_name" name="customer_name" maxlength="100" required value="<?= e($_POST['customer_name'] ?? '') ?>"></div><div><label for="email">Email address *</label><input id="email" type="email" name="email" maxlength="150" required value="<?= e($_POST['email'] ?? '') ?>"></div></div>
    <div class="form-row"><div><label for="phone">Phone number *</label><input id="phone" type="tel" name="phone" maxlength="20" required value="<?= e($_POST['phone'] ?? '') ?>"></div><div><label for="quantity">Quantity *</label><input id="quantity" type="number" name="quantity" min="1" max="20" value="<?= e($_POST['quantity'] ?? '1') ?>" required></div></div>
    <label for="delivery_date">Preferred delivery date *</label><input id="delivery_date" type="date" name="delivery_date" min="<?= date('Y-m-d') ?>" value="<?= e($_POST['delivery_date'] ?? '') ?>" required>
    <label for="notes">Special instructions</label><textarea id="notes" name="notes" rows="4" maxlength="1000" placeholder="Flavour notes, message on cake, or anything else..."><?= e($_POST['notes'] ?? '') ?></textarea>
    <button class="btn btn-primary btn-full" type="submit">Submit cake request <span>→</span></button>
    <p class="form-footnote">This is a request, not an online payment. Our team will contact you to confirm availability and the final amount.</p>
</form></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
