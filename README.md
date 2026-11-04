# Cake Shop Management System

A simple college project built with **HTML, CSS, PHP and MySQL**, intended to run locally using **XAMPP**.

## Features
- Responsive cake shop homepage and cake catalogue
- Cake details loaded from MySQL
- Customer order form with server-side validation
- Contact form saved to MySQL
- Basic admin login
- Admin dashboard to add/delete cakes and view/update orders

## Requirements
- XAMPP (Apache + MySQL)
- PHP 8.0 or later
- A modern browser

## Setup in XAMPP
1. Copy/clone this repository into `C:\\xampp\\htdocs\\CakeShop`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database/cake_shop.sql`.
5. Check database settings in `config/db.php` (default XAMPP settings: host `localhost`, user `root`, blank password).
6. Visit `http://localhost/CakeShop/`.

## Demo admin login
- Username: `admin`
- Password: `admin123`

Change this password before using the project beyond a local classroom demonstration.

## Project structure
```
CakeShop/
├── admin/
├── assets/
│   ├── css/
│   └── images/
├── config/
├── database/
├── includes/
├── index.php
├── cakes.php
├── about.php
├── contact.php
└── order.php
```

## Academic scope
This is a beginner-friendly PHP/MySQL project demonstrating database connectivity, CRUD operations, form handling, sessions and basic UI design. It does not include real payment processing or online delivery integrations.
