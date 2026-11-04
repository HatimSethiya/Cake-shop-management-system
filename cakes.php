<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Our Cakes | Sweet Crumbs';
$activePage = 'cakes';
$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare('SELECT id, name, description, price, image FROM cakes WHERE is_available = 1 AND (name LIKE :term OR description LIKE :term) ORDER BY name');
    $stmt->execute(['term' => '%' . $search . '%']);
} else {
    $stmt = $pdo->query('SELECT id, name, description, price, image FROM cakes WHERE is_available = 1 ORDER BY name');
}
$cakes = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/flash.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">FRESH FROM OUR OVEN</span><h1>Find your <em>favourite.</em></h1><p>Thoughtfully baked cakes for birthdays, celebrations and just-because days.</p></div></section>
<section class="section">
    <div class="container">
        <form class="search-form" method="get" action="cakes.php"><label class="sr-only" for="q">Search cakes</label><input id="q" type="search" name="q" value="<?= e($search) ?>" placeholder="Search by cake name..."><button class="btn btn-dark" type="submit">Search</button><?php if ($search !== ''): ?><a class="text-link" href="cakes.php">Clear</a><?php endif; ?></form>
        <?php if ($cakes): ?>
            <div class="cake-grid">
                <?php foreach ($cakes as $cake): ?>
                    <article class="cake-card">
                        <a class="cake-image" href="order.php?cake_id=<?= (int)$cake['id'] ?>"><img src="<?= e($cake['image']) ?>" alt="<?= e($cake['name']) ?>" loading="lazy"><span class="image-arrow">↗</span></a>
                        <div class="cake-info"><div><h3><?= e($cake['name']) ?></h3><p><?= e($cake['description']) ?></p></div><strong>₹<?= number_format((float)$cake['price'], 2) ?></strong></div>
                        <a class="card-order" href="order.php?cake_id=<?= (int)$cake['id'] ?>">Order this cake <span>→</span></a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state"><span>🔎</span><h3>No cakes found</h3><p>Try another search, or check back for our next fresh batch.</p></div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
