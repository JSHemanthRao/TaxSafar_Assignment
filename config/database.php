<?php
/**
 * Database Connection File - Wasmer Edge Optimized
 */

// Use getenv() to pull from your Wasmer Secrets
// If the secret isn't found, it defaults to your specific DB values
define('DB_HOST', getenv('DB_HOST') ?: 'db.fr-pari1.bengt.wasmernet.com');
define('DB_PORT', getenv('DB_PORT') ?: '10272');
define('DB_USER', getenv('DB_USERNAME') ?: '34e861a376d88000d6d6506fe819');
define('DB_PASS', getenv('DB_PASSWORD') ?: '069e34e8-61a3-77ec-8000-eb256c269db7');
define('DB_NAME', getenv('DB_DATABASE') ?: 'taxsafar');

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Standard PDO connection string for MySQL on a custom port
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS inquiries (
        id INT PRIMARY KEY AUTO_INCREMENT,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        mobile VARCHAR(15) NOT NULL,
        city VARCHAR(50) NOT NULL,
        service VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

} catch (PDOException $e) {
    // This will print the error to your Wasmer Logs so you can debug
    error_log("Connection failed: " . $e->getMessage());
    die("Database Connection Error. Please check the App Logs.");
}
?>
