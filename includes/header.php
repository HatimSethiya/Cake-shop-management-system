<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Sweet Crumbs | Freshly Baked Happiness';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Freshly baked cakes for birthdays, celebrations and everyday happiness.">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="index.php"><span class="brand-mark">✿</span> Sweet Crumbs<span class="brand-dot">.</span></a>
        <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">☰</button>
        <nav class="main-nav" aria-label="Main navigation">
            <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="index.php">Home</a>
            <a class="<?= $activePage === 'cakes' ? 'active' : '' ?>" href="cakes.php">Our Cakes</a>
            <a class="<?= $activePage === 'about' ? 'active' : '' ?>" href="about.php">About</a>
            <a class="<?= $activePage === 'contact' ? 'active' : '' ?>" href="contact.php">Contact</a>
            <a class="nav-order" href="order.php">Order a Cake <span>→</span></a>
            <a class="nav-order nav-admin" href="admin/login.php">Admin Dashboard <span>→</span></a>
        </nav>
    </div>
</header>
<main>
