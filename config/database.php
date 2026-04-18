<?php
/**
 * Database Connection File
 * Using PDO for secure database operations
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'taxsafar_db');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Connect to MySQL without specifying the database first
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        $options
    );

    // Create database if it does not exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");

    // Create admins table if missing
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS admins (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Create inquiries table if missing
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS inquiries (
            id INT PRIMARY KEY AUTO_INCREMENT,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            mobile VARCHAR(15) NOT NULL,
            city VARCHAR(50) NOT NULL,
            service VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    // Ensure default admin exists
    $stmt = $pdo->prepare('SELECT id FROM admins WHERE email = ?');
    $stmt->execute(['admin@taxsafar.com']);

    if (!$stmt->fetch()) {
        $defaultPassword = password_hash('Admin@123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO admins (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute(['Admin User', 'admin@taxsafar.com', $defaultPassword]);
    }
} catch (PDOException $e) {
    die('Database Connection Error: ' . $e->getMessage());
}
?>
