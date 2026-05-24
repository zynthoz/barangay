<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT l.log_id, u.full_name, l.action, l.date_time 
          FROM tbl_logs l 
          JOIN tbl_users u ON l.user_id = u.user_id 
          WHERE l.action LIKE '%$search%' OR u.full_name LIKE '%$search%'
          ORDER BY l.date_time DESC";
$result = mysqli_query($conn, $query);

$current_page = 'logs';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Logs</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Logs</span>
        </div>

        <div class="page-header">
            <h1>System Logs</h1>
        </div>

        <form method="GET" class="filter-bar" id="logs-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by action or user…" value="<?php echo $search; ?>" id="logs-search">
            <button type="submit" class="btn-secondary">Search</button>
        </form>

        <table class="data-table" id="logs-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Date &amp; Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['log_id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['action']; ?></td>
                    <td><?php echo date('M j, Y · g:ia', strtotime($row['date_time'])); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="4"><div class="empty-state"><p>No logs found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
