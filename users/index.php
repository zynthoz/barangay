<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM tbl_users WHERE full_name LIKE '%$search%' OR username LIKE '%$search%' ORDER BY user_id DESC";
$result = mysqli_query($conn, $query);

$current_page = 'users';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Users</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Users</span>
        </div>

        <div class="page-header">
            <h1>Users</h1>
            <a href="add.php" class="btn-accent" id="add-user-btn">+ Add User</a>
        </div>

        <form method="GET" class="filter-bar" id="users-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by name or username…" value="<?php echo $search; ?>" id="users-search">
            <button type="submit" class="btn-secondary">Search</button>
        </form>

        <table class="data-table" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['user_id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="6"><div class="empty-state"><p>No users found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
