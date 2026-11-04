<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
$pageTitle = 'Manage Cakes | Sweet Crumbs';
$errors = [];
$editCake = null;

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
if ($editId) {
    $stmt = $pdo->prepare('SELECT id, name, description, price, image FROM cakes WHERE id = ? LIMIT 1');
    $stmt->execute([$editId]);
    $editCake = $stmt->fetch();
    if (!$editCake) {
        set_flash('error', 'Cake not found.');
        redirect('manage-cakes.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please refresh and try again.';
    } elseif (in_array(($_POST['action'] ?? ''), ['add', 'update'], true)) {
        $action = $_POST['action'];
        $id = $action === 'update' ? filter_input(INPUT_POST, 'cake_id', FILTER_VALIDATE_INT) : null;
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $image = trim($_POST['image'] ?? '');
        if ($action === 'update' && !$id) $errors[] = 'Invalid cake selected.';
        if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Enter a cake name (up to 120 characters).';
        if ($description === '' || mb_strlen($description) > 500) $errors[] = 'Enter a description (up to 500 characters).';
        if ($price === false || $price === null || $price <= 0 || $price > 100000) $errors[] = 'Enter a valid price.';
        if (!filter_var($image, FILTER_VALIDATE_URL) || !in_array(parse_url($image, PHP_URL_SCHEME), ['http','https'], true) || strlen($image) > 500) $errors[] = 'Enter a valid image URL.';
        if (!$errors) {
            if ($action === 'update') {
                $stmt = $pdo->prepare('UPDATE cakes SET name = ?, description = ?, price = ?, image = ? WHERE id = ?');
                $stmt->execute([$name, $description, $price, $image, $id]);
                set_flash('success', 'Cake updated successfully.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO cakes (name, description, price, image) VALUES (?, ?, ?, ?)');
                $stmt->execute([$name, $description, $price, $image]);
                set_flash('success', 'Cake added successfully.');
            }
            redirect('manage-cakes.php');
        }
        if ($action === 'update') {
            $editCake = ['id' => $id, 'name' => $name, 'description' => $description, 'price' => $price, 'image' => $image];
        }
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $id = filter_input(INPUT_POST, 'cake_id', FILTER_VALIDATE_INT);
        if (!$id) $errors[] = 'Invalid cake selected.';
        else {
            try {
                $stmt = $pdo->prepare('DELETE FROM cakes WHERE id = ?');
                $stmt->execute([$id]);
                set_flash('success', 'Cake deleted successfully.');
                redirect('manage-cakes.php');
            } catch (PDOException $ex) {
                if ($ex->getCode() === '23000') $errors[] = 'This cake has orders and cannot be deleted. Mark it unavailable in the database instead.';
                else throw $ex;
            }
        }
    }
}
$cakes = $pdo->query('SELECT id, name, price, is_available FROM cakes ORDER BY id DESC')->fetchAll();
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/flash.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">STAFF AREA</span><h1>Manage <em>cakes.</em></h1><p>Add new cakes to the customer catalogue or edit/remove an existing listing.</p></div></section>
<section class="section"><div class="container admin-content">
<div class="admin-topbar"><div><h2>Cake catalogue</h2><p><?= count($cakes) ?> cake(s) in database.</p></div><a class="text-link" href="dashboard.php">← Dashboard</a></div>
<?php if ($errors): ?><div class="alert alert-error"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="form-card admin-add-form" method="post">
<h2><?= $editCake ? 'Edit cake' : 'Add a cake' ?></h2>
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<input type="hidden" name="action" value="<?= $editCake ? 'update' : 'add' ?>">
<?php if ($editCake): ?><input type="hidden" name="cake_id" value="<?= (int)$editCake['id'] ?>"><?php endif; ?>
<div class="form-row"><div><label for="name">Cake name</label><input id="name" name="name" maxlength="120" required value="<?= e($editCake['name'] ?? '') ?>"></div><div><label for="price">Price (₹)</label><input id="price" name="price" type="number" min="1" max="100000" step=".01" required value="<?= e((string)($editCake['price'] ?? '')) ?>"></div></div>
<label for="description">Description</label><input id="description" name="description" maxlength="500" required value="<?= e($editCake['description'] ?? '') ?>">
<label for="image">Image URL</label><input id="image" name="image" type="url" maxlength="500" required placeholder="https://example.com/cake.jpg" value="<?= e($editCake['image'] ?? '') ?>">
<button class="btn btn-primary" type="submit"><?= $editCake ? 'Save changes' : 'Add cake' ?> <span><?= $editCake ? '✓' : '+' ?></span></button>
<?php if ($editCake): ?> <a class="btn btn-dark" href="manage-cakes.php">Cancel edit</a><?php endif; ?>
</form>
<div class="admin-table-wrap"><h2>Existing cakes</h2><div class="table-scroll"><table class="admin-table"><thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Available</th><th>Action</th></tr></thead><tbody>
<?php foreach ($cakes as $cake): ?><tr><td><?= (int)$cake['id'] ?></td><td><?= e($cake['name']) ?></td><td>₹<?= number_format((float)$cake['price'],2) ?></td><td><?= (int)$cake['is_available'] ? 'Yes' : 'No' ?></td><td><div class="cake-actions"><a class="btn btn-small btn-primary" href="manage-cakes.php?edit=<?= (int)$cake['id'] ?>">Edit</a><form method="post" onsubmit="return confirm('Delete this cake?');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="cake_id" value="<?= (int)$cake['id'] ?>"><button class="btn btn-small btn-danger" type="submit">Delete</button></form></div></td></tr><?php endforeach; ?>
</tbody></table></div></div></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
