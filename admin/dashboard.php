<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

try {
    // Get total inquiries
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM inquiries');
    $total_inquiries = $stmt->fetch()['count'];

    // Get new inquiries
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM inquiries WHERE status = "new"');
    $new_inquiries = $stmt->fetch()['count'];

    // Get contacted inquiries
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM inquiries WHERE status = "contacted"');
    $contacted_inquiries = $stmt->fetch()['count'];

    // Get closed inquiries
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM inquiries WHERE status = "closed"');
    $closed_inquiries = $stmt->fetch()['count'];

    // Get recent inquiries
    $stmt = $pdo->query('SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5');
    $recent_inquiries = $stmt->fetchAll();
} catch (Exception $e) {
    die('Error fetching dashboard data: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TaxSafar</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body class="admin-page">
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container">
            <div class="admin-navbar-brand">
                <h1>TaxSafar Admin</h1>
            </div>
            <div class="admin-navbar-links">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                <a href="manage_inquiries.php" class="nav-link">Manage Inquiries</a>
                <a href="logout.php" class="nav-link logout">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="admin-container">
        <h2>Dashboard</h2>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card total">
                <h3>Total Inquiries</h3>
                <p class="stat-number"><?php echo $total_inquiries; ?></p>
            </div>
            <div class="stat-card new">
                <h3>New Inquiries</h3>
                <p class="stat-number"><?php echo $new_inquiries; ?></p>
            </div>
            <div class="stat-card contacted">
                <h3>Contacted</h3>
                <p class="stat-number"><?php echo $contacted_inquiries; ?></p>
            </div>
            <div class="stat-card closed">
                <h3>Closed</h3>
                <p class="stat-number"><?php echo $closed_inquiries; ?></p>
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="recent-inquiries">
            <h3>Recent Inquiries</h3>
            <?php if (count($recent_inquiries) > 0): ?>
                <table class="inquiries-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_inquiries as $inquiry): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inquiry['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['service']); ?></td>
                                <td><span class="status-badge status-<?php echo $inquiry['status']; ?>"><?php echo ucfirst($inquiry['status']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No inquiries yet.</p>
            <?php endif; ?>
            <a href="manage_inquiries.php" class="view-all-btn">View All Inquiries &rarr;</a>
        </div>
    </div>

    <script src="../public/js/script.js"></script>
</body>
</html>
