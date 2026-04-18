<?php
/**
 * CHANGE ADMIN PASSWORD
 * 
 * How to change the admin password:
 * 1. Run this script in browser: http://localhost/TaxSafar_Assignment/change_admin_password.php
 * 2. Or run directly: php change_admin_password.php
 * 
 * This is a utility script for changing admin password.
 * After changing password in production, DELETE THIS FILE for security.
 */

require_once 'config/database.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $email = trim($_POST['email'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $error = '';
    $success = '';

    // Validation
    if (empty($email)) {
        $error = 'Email is required';
    } elseif (empty($new_password)) {
        $error = 'New password is required';
    } elseif (strlen($new_password) < 8) {
        $error = 'Password must be at least 8 characters';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Hash the password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        try {
            // Update password
            $stmt = $pdo->prepare('UPDATE admins SET password = ? WHERE email = ?');
            $result = $stmt->execute([$hashed_password, $email]);

            if ($stmt->rowCount() > 0) {
                $success = 'Password updated successfully for ' . htmlspecialchars($email);
            } else {
                $error = 'Email not found in system';
            }
        } catch (Exception $e) {
            $error = 'Error updating password: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Admin Password - TaxSafar</title>
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .password-change-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="password-change-container">
        <h2>Change Admin Password</h2>

        <div class="warning">
            <strong>Important:</strong><br>
            After changing the password, DELETE this file from the server for security.
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" 
                       placeholder="Minimum 8 characters" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="submit-btn">Change Password</button>
        </form>

        <p style="margin-top: 2rem; text-align: center;">
            <a href="public/index.php">&larr; Back to Home</a>
        </p>
    </div>
</body>
</html>
