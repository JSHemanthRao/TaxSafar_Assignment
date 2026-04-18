<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$success_message = '';
$error_message = '';
$search_query = '';
$status_filter = '';

// Get search and filter parameters
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $inquiry_id = intval($_POST['inquiry_id'] ?? 0);
    
    if ($inquiry_id > 0) {
        try {
            $stmt = $pdo->prepare('DELETE FROM inquiries WHERE id = ?');
            $stmt->execute([$inquiry_id]);
            $success_message = 'Inquiry deleted successfully.';
        } catch (Exception $e) {
            $error_message = 'Error deleting inquiry.';
        }
    }
}

// Handle update status or other details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $inquiry_id = intval($_POST['inquiry_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    
    if ($inquiry_id > 0 && in_array($status, ['new', 'contacted', 'closed'])) {
        try {
            $stmt = $pdo->prepare('UPDATE inquiries SET status = ? WHERE id = ?');
            $stmt->execute([$status, $inquiry_id]);
            $success_message = 'Inquiry updated successfully.';
        } catch (Exception $e) {
            $error_message = 'Error updating inquiry.';
        }
    }
}

// Build query
$query = 'SELECT * FROM inquiries WHERE 1=1';
$params = [];

if (!empty($search_query)) {
    $query .= ' AND (full_name LIKE ? OR email LIKE ? OR mobile LIKE ?)';
    $search_param = '%' . $search_query . '%';
    $params = [$search_param, $search_param, $search_param];
}

if (!empty($status_filter)) {
    $query .= ' AND status = ?';
    $params[] = $status_filter;
}

$query .= ' ORDER BY created_at DESC';

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $inquiries = $stmt->fetchAll();
} catch (Exception $e) {
    $error_message = 'Error fetching inquiries.';
    $inquiries = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inquiries - TaxSafar</title>
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
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-link logout">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Inquiries Content -->
    <div class="admin-container">
        <h2>Manage Inquiries</h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Search and Filter -->
        <div class="search-filter-section">
            <form method="GET" action="" class="search-form">
                <input type="text" name="search" placeholder="Search by name, email, or mobile..." 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                
                <select name="status">
                    <option value="">All Status</option>
                    <option value="new" <?php echo ($status_filter === 'new') ? 'selected' : ''; ?>>New</option>
                    <option value="contacted" <?php echo ($status_filter === 'contacted') ? 'selected' : ''; ?>>Contacted</option>
                    <option value="closed" <?php echo ($status_filter === 'closed') ? 'selected' : ''; ?>>Closed</option>
                </select>
                
                <button type="submit" class="search-btn">Search</button>
                <a href="manage_inquiries.php" class="clear-btn">Clear</a>
            </form>
        </div>

        <!-- Inquiries Table -->
        <?php if (count($inquiries) > 0): ?>
            <div class="table-wrapper">
                <table class="inquiries-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>City</th>
                            <th>Service</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inquiries as $inquiry): ?>
                            <tr>
                                <td><?php echo $inquiry['id']; ?></td>
                                <td><?php echo htmlspecialchars($inquiry['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['city']); ?></td>
                                <td><?php echo htmlspecialchars($inquiry['service']); ?></td>
                                <td>
                                    <button class="view-msg-btn" onclick="viewMessage('<?php echo htmlspecialchars($inquiry['message']); ?>')">View</button>
                                </td>
                                <td>
                                    <form method="POST" action="" class="inline-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="new" <?php echo ($inquiry['status'] === 'new') ? 'selected' : ''; ?>>New</option>
                                            <option value="contacted" <?php echo ($inquiry['status'] === 'contacted') ? 'selected' : ''; ?>>Contacted</option>
                                            <option value="closed" <?php echo ($inquiry['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></td>
                                <td>
                                    <button class="delete-btn" onclick="confirmDelete(<?php echo $inquiry['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Delete Form (Hidden) -->
            <form id="deleteForm" method="POST" action="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="inquiry_id" id="deleteInquiryId">
            </form>
        <?php else: ?>
            <p class="no-data">No inquiries found.</p>
        <?php endif; ?>
    </div>

    <script src="../public/js/script.js"></script>
    <script>
        function confirmDelete(inquiryId) {
            if (confirm('Are you sure you want to delete this inquiry?')) {
                document.getElementById('deleteInquiryId').value = inquiryId;
                document.getElementById('deleteForm').submit();
            }
        }

        function viewMessage(message) {
            alert('Message:\n\n' + message);
        }
    </script>
</body>
</html>
