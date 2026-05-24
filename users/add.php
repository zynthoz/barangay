<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $query = "INSERT INTO tbl_users (full_name, role, username, password, email) VALUES ('$full_name', '$role', '$username', '$password', '$email')";
    mysqli_query($conn, $query);

    $action = "Added user: " . $full_name;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    header("Location: index.php");
    exit();
}

$current_page = 'users';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Add User</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <a href="index.php">Users</a>
            <span class="sep">/</span>
            <span>Add</span>
        </div>

        <div class="page-header">
            <h1>Add User</h1>
        </div>

        <form method="POST" id="add-user-form">
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role" class="form-select" style="width:100%" required>
                    <option value="Employee">Employee</option>
                    <option value="Admin">Admin</option>
                    <option value="Resident">Resident</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="text" id="password" name="password" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" style="width:100%" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="save-user-btn">Save User</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
