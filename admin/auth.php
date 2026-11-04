<?php
require_once __DIR__ . '/../includes/functions.php';

if (!is_admin()) {
    set_flash('error', 'Please sign in to access the admin area.');
    redirect('login.php');
}
