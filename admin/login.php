<?php
session_start();
require_once '../config/database.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validation
    if (empty($email) || empty($password)) {
        $error_message = 'Email and Password are required';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = ?');
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error_message = 'Invalid email or password';
            }
        } catch (Exception $e) {
            $error_message = 'Login error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TaxSafar</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h2>TaxSafar Admin Login</h2>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="submit-btn">Login</button>
            </form>

            <p class="login-footer">
                Demo Credentials:<br>
                Email: admin@taxsafar.com<br>
                Password: Admin@123
            </p>

            <p class="back-link">
                <a href="../public/index.php">&larr; Back to Home</a>
            </p>
        </div>
    </div>

    <script src="../public/js/script.js"></script>
</body>
</html>
