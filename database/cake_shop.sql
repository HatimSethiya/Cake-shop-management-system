-- Cake Shop Management System
-- Import this file through phpMyAdmin (XAMPP).
CREATE DATABASE IF NOT EXISTS cake_shop_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE cake_shop_db;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cakes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description VARCHAR(500) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500) NOT NULL,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cake_id INT UNSIGNED NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    quantity TINYINT UNSIGNED NOT NULL,
    delivery_date DATE NOT NULL,
    notes VARCHAR(1000) NULL,
    status ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_cake FOREIGN KEY (cake_id) REFERENCES cakes(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message VARCHAR(2000) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO cakes (name, description, price, image, is_available) VALUES
('Chocolate Truffle', 'Rich chocolate layers with silky truffle frosting.', 650.00, 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=900&q=85', 1),
('Strawberry Dream', 'Soft vanilla sponge with fresh strawberry cream.', 580.00, 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=900&q=85', 1),
('Classic Red Velvet', 'A tender red velvet cake with smooth cream cheese icing.', 720.00, 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?auto=format&fit=crop&w=900&q=85', 1),
('Vanilla Celebration', 'Light vanilla sponge, whipped cream and delicate decoration.', 550.00, 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=900&q=85', 1),
('Coffee Walnut', 'Coffee-infused cream with a little walnut crunch.', 690.00, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=900&q=85', 1),
('Black Forest', 'Chocolate sponge, cherries and classic whipped cream.', 640.00, 'https://images.unsplash.com/photo-1542826438-bd32f43d626f?auto=format&fit=crop&w=900&q=85', 1);

-- Demo admin: username admin, password admin123.
-- Password hash is generated with PHP password_hash('admin123', PASSWORD_DEFAULT).
INSERT INTO admins (username, password_hash)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username = VALUES(username);
