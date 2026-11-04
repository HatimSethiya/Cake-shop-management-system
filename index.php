<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Sweet Crumbs | Cakes Made for Happy Moments';
$activePage = 'home';

$stmt = $pdo->query('SELECT id, name, description, price, image FROM cakes WHERE is_available = 1 ORDER BY id DESC LIMIT 3');
$featuredCakes = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/flash.php';
?>
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow"><span></span> BAKED FRESH, SHARED WITH LOVE</span>
            <h1>A little slice of <em>happiness</em> in every bite.</h1>
            <p>From everyday treats to once-in-a-lifetime celebrations, find the cake that makes your moment sweeter.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="cakes.php">Explore our cakes <span>→</span></a>
                <a class="text-link" href="about.php">Our little story <span>↗</span></a>
            </div>
            <div class="hero-note"><span class="note-stars">★★★★★</span><span>Made fresh daily · Baked with care</span></div>
        </div>
        <div class="hero-art">
            <div class="hero-photo">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1000&q=85" alt="Beautiful decorated celebration cake">
            </div>
            <div class="floating-note"><span>♡</span><div><strong>Made with love</strong><small>For your sweetest days</small></div></div>
            <span class="hero-sparkle sparkle-one">✳</span>
            <span class="hero-sparkle sparkle-two">✧</span>
        </div>
    </div>
    <div class="hero-bottom container"><span>01 / 03</span><span class="hero-line"></span><span>THE SWEET LIFE STARTS HERE</span></div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-heading">
            <div><span class="eyebrow">A LITTLE SOMETHING FOR EVERYONE</span><h2>Our <em>favourites</em></h2></div>
            <a class="text-link" href="cakes.php">View all cakes <span>→</span></a>
        </div>
        <?php if ($featuredCakes): ?>
            <div class="cake-grid">
                <?php foreach ($featuredCakes as $cake): ?>
                    <article class="cake-card">
                        <a class="cake-image" href="order.php?cake_id=<?= (int)$cake['id'] ?>">
                            <img src="<?= e($cake['image']) ?>" alt="<?= e($cake['name']) ?>" loading="lazy">
                            <span class="image-arrow">↗</span>
                        </a>
                        <div class="cake-info">
                            <div><h3><?= e($cake['name']) ?></h3><p><?= e($cake['description']) ?></p></div>
                            <strong>₹<?= number_format((float)$cake['price'], 2) ?></strong>
                        </div>
                        <a class="card-order" href="order.php?cake_id=<?= (int)$cake['id'] ?>">Order this cake <span>→</span></a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state"><span>🍰</span><h3>Our cake stand is getting ready!</h3><p>Please visit again soon for our freshly baked selection.</p></div>
        <?php endif; ?>
    </div>
</section>

<section class="story-strip">
    <div class="container story-grid">
        <div class="story-image"><img src="https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?auto=format&fit=crop&w=900&q=85" alt="Freshly baked cake being prepared" loading="lazy"></div>
        <div class="story-copy"><span class="eyebrow">NOT JUST CAKE. A LITTLE JOY.</span><h2>Good days are made of <em>good cake.</em></h2><p>We believe the best celebrations aren't always grand. Sometimes, all you need is your favourite people and a really good slice of cake.</p><a class="btn btn-dark" href="about.php">Get to know us <span>→</span></a></div>
    </div>
</section>

<section class="cta-section"><div class="container cta-inner"><span class="eyebrow">YOUR NEXT CELEBRATION STARTS HERE</span><h2>Let's make it a <em>sweet</em> one.</h2><p>Tell us what you're celebrating. We'll take care of the cake.</p><a class="btn btn-primary" href="order.php">Place an order <span>→</span></a></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
