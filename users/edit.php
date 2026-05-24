<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$id = $_GET['id'];
$query = "SELECT * FROM tbl_users WHERE user_id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $update_query = "UPDATE tbl_users SET full_name='$full_name', role='$role', username='$username', password='$password', email='$email' WHERE user_id=$id";
    mysqli_query($conn, $update_query);

    $action = "Edited user: " . $full_name;
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
    <title>BRMS — Edit User</title>
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
            <span>Edit</span>
        </div>

        <div class="page-header">
            <h1>Edit User</h1>
        </div>

        <form method="POST" id="edit-user-form">
            <div class="form-group">
                <label class="form-label" for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" style="width:100%" value="<?php echo $user['full_name']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="role">Role</label>
                <select id="role" name="role" class="form-select" style="width:100%" required>
                    <option value="Employee" <?php if($user['role']=='Employee') echo 'selected'; ?>>Employee</option>
                    <option value="Admin" <?php if($user['role']=='Admin') echo 'selected'; ?>>Admin</option>
                    <option value="Resident" <?php if($user['role']=='Resident') echo 'selected'; ?>>Resident</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" style="width:100%" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="text" id="password" name="password" class="form-control" style="width:100%" value="<?php echo $user['password']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" style="width:100%" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="update-user-btn">Update User</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
