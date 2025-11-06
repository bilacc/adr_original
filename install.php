<?php
// Simple installer to create database tables and first admin user.
// Run once. After success delete this file or protect it.
require_once __DIR__ . '/lib/functions.php';

// Ensure DB inited
if (!get_conf('use_database')) {
    die('Database usage is disabled in configuration.');
}
echo "<pre>\nInstaller starting...\n";

// get DB connection
$db = new Database();

// Create tables
$queries = [];

// admin_users
$queries[] = "CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// items (properties) minimal
$queries[] = "CREATE TABLE IF NOT EXISTS `items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_hr` VARCHAR(255),
  `title_en` VARCHAR(255),
  `description` TEXT,
  `price` DECIMAL(12,2) DEFAULT 0,
  `published` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// site_photos
$queries[] = "CREATE TABLE IF NOT EXISTS `site_photos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(100) NOT NULL,
  `table_id` INT UNSIGNED NOT NULL,
  `photo_name` VARCHAR(255) NOT NULL,
  `orderby` INT DEFAULT 0,
  `tlocrt` VARCHAR(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute
foreach ($queries as $q) {
    try {
        $db->exec($q);
        echo "OK: table/query executed\n";
    } catch (Exception $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
}

// Create an admin user
$username = isset($_REQUEST['user']) ? trim($_REQUEST['user']) : 'admin';
$password = isset($_REQUEST['pass']) ? trim($_REQUEST['pass']) : 'admin';

// warn if default credentials used
echo "\nCreating admin account:\nUsername: {$username}\nPassword: {$password}\n";
$hash = password_hash($password, PASSWORD_DEFAULT);

$exists = $db->queryRow("SELECT id FROM admin_users WHERE username = :u", ['u' => $username]);
if ($exists) {
    echo "Admin user already exists, skipping insert.\n";
} else {
    $db->exec("INSERT INTO admin_users (username, password, name, email) VALUES (:u, :p, :n, :e)", ['u' => $username, 'p' => $hash, 'n' => 'Administrator', 'e' => 'admin@localhost']);
    echo "Admin user created. You may now login at /admin/login/ using these credentials.\n";
}

echo "\nInstaller finished. IMPORTANT: delete install.php or protect it.\n</pre>";
?>