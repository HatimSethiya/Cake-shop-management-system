<?php $flash = get_flash(); ?>
<?php if ($flash): ?>
    <div class="container">
        <div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
    </div>
<?php endif; ?>
