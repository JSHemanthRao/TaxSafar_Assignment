<?php
/**
 * Database Connection File - Wasmer Optimized
 * Using Environment Variables for Secure Connection
 */

// Database configuration using Wasmer Secrets
define('DB_HOST', getenv('DB_HOST') ?: 'db.fr-pari1.bengt.wasmernet.com');
define('DB_PORT', getenv('DB_PORT') ?: '10272');
define('DB_USER', getenv('DB_USERNAME') ?: '34e861a376d88000d6d6506fe819');
define('DB_PASS', getenv('DB_PASSWORD') ?: '069e34e8-61a3-77ec-8000-eb256c269db7');
define('DB_NAME', getenv('DB_DATABASE') ?: 'taxsafar');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Connect using the Wasmer-provided credentials
    // Note: We include the PORT and the DATABASE name in the DSN
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Create tables if they don't exist (Wasmer Managed DBs come pre-created, but tables might not)
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS admins (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

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
    // If it fails, it will show the specific error in your Wasmer Logs
    die('Database Connection Error: ' . $e->getMessage());
}
?>
