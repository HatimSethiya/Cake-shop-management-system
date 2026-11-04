<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
$pageTitle = 'Manage Orders | Sweet Crumbs';
$errors = [];
$statuses = ['Pending','Confirmed','Completed','Cancelled'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors[] = 'Your session expired. Please refresh and try again.';
    else {
        $id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        if (!$id || !in_array($status, $statuses, true)) $errors[] = 'Invalid order or status.';
        else {
            $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
            $stmt->execute([$status, $id]);
            set_flash('success', 'Order status updated.');
            redirect('manage-orders.php');
        }
    }
}
$orders = $pdo->query('SELECT o.*, c.name AS cake_name, c.price FROM orders o JOIN cakes c ON c.id = o.cake_id ORDER BY o.created_at DESC')->fetchAll();
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/flash.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">STAFF AREA</span><h1>Manage <em>orders.</em></h1><p>Review customer requests and keep order status up to date.</p></div></section>
<section class="section"><div class="container admin-content"><div class="admin-topbar"><div><h2>All customer orders</h2><p><?= count($orders) ?> order(s) received.</p></div><a class="text-link" href="dashboard.php">← Dashboard</a></div>
<?php if ($errors): ?><div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
<?php if ($orders): ?><div class="orders-list"><?php foreach ($orders as $order): ?><article class="order-card"><div class="order-card-head"><div><span class="eyebrow">ORDER #<?= (int)$order['id'] ?></span><h3><?= e($order['cake_name']) ?></h3></div><span class="status status-<?= strtolower(e($order['status'])) ?>"><?= e($order['status']) ?></span></div>
<div class="order-meta"><div><small>CUSTOMER</small><strong><?= e($order['customer_name']) ?></strong></div><div><small>CONTACT</small><strong><?= e($order['email']) ?> · <?= e($order['phone']) ?></strong></div><div><small>QUANTITY</small><strong><?= (int)$order['quantity'] ?></strong></div><div><small>DELIVERY DATE</small><strong><?= e($order['delivery_date']) ?></strong></div><div><small>ESTIMATED TOTAL</small><strong>₹<?= number_format((float)$order['price'] * (int)$order['quantity'],2) ?></strong></div></div>
<?php if ($order['notes']): ?><p class="order-notes"><strong>Notes:</strong> <?= e($order['notes']) ?></p><?php endif; ?>
<form class="status-form" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>"><label for="status-<?= (int)$order['id'] ?>">Update status</label><select id="status-<?= (int)$order['id'] ?>" name="status"><?php foreach ($statuses as $status): ?><option value="<?= e($status) ?>" <?= $order['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select><button class="btn btn-small btn-dark" type="submit">Save status</button></form></article><?php endforeach; ?></div><?php else: ?><div class="empty-state"><span>🧁</span><h3>No orders yet</h3><p>New customer requests will appear here.</p></div><?php endif; ?></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
