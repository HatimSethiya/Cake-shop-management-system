<?php
declare(strict_types=1);

/*
 * Local XAMPP database configuration.
 * Update these values if your MySQL username/password differs.
 */

$host = '127.0.0.1';
$dbName = 'cake_shop_db';
$username = 'root';
$password = '';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
        $username,
        $password,
        $options
    );
} catch (PDOException $exception) {
    error_log('Database connection failed: ' . $exception->getMessage());
    http_response_code(500);
    exit('Database connection failed. Import database/cake_shop.sql and check config/db.php.');
}
