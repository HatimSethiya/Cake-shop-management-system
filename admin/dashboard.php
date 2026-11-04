<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

$pageTitle = 'Admin Dashboard | Sweet Crumbs';
$totalCakes = (int)$pdo->query('SELECT COUNT(*) FROM cakes')->fetchColumn();
$totalOrders = (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pendingOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$recentOrders = $pdo->query('SELECT o.id, o.customer_name, o.quantity, o.delivery_date, o.status, c.name AS cake_name FROM orders o JOIN cakes c ON c.id = o.cake_id ORDER BY o.created_at DESC LIMIT 8')->fetchAll();

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/flash.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">STAFF AREA</span><h1>Hello, <em><?= e($_SESSION['admin_username'] ?? 'Admin') ?>.</em></h1><p>Here's a quick look at what's happening at the shop.</p></div></section>
<section class="section"><div class="container">
    <div class="admin-topbar"><div><h2>Dashboard</h2><p>Manage your shop from one place.</p></div><a class="btn btn-dark" href="logout.php">Log out <span>→</span></a></div>
    <div class="stats-grid"><div class="stat-card"><span>CAKES LISTED</span><strong><?= $totalCakes ?></strong></div><div class="stat-card"><span>TOTAL ORDERS</span><strong><?= $totalOrders ?></strong></div><div class="stat-card"><span>PENDING ORDERS</span><strong><?= $pendingOrders ?></strong></div></div>
    <div class="admin-actions"><a class="btn btn-primary" href="manage-cakes.php">Manage cakes <span>→</span></a><a class="btn btn-dark" href="manage-orders.php">Manage orders <span>→</span></a></div>
    <div class="admin-table-wrap"><h2>Recent orders</h2><div class="table-scroll"><table class="admin-table"><thead><tr><th>Order</th><th>Customer</th><th>Cake</th><th>Qty</th><th>Delivery</th><th>Status</th></tr></thead><tbody>
    <?php if ($recentOrders): foreach ($recentOrders as $order): ?><tr><td>#<?= (int)$order['id'] ?></td><td><?= e($order['customer_name']) ?></td><td><?= e($order['cake_name']) ?></td><td><?= (int)$order['quantity'] ?></td><td><?= e($order['delivery_date']) ?></td><td><span class="status status-<?= strtolower(e($order['status'])) ?>"><?= e($order['status']) ?></span></td></tr><?php endforeach; else: ?><tr><td colspan="6">No orders received yet.</td></tr><?php endif; ?>
    </tbody></table></div></div>
</div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
