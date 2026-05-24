<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM tbl_users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-header">
            <h1 class="login-title">Barangay Records</h1>
            <p class="login-subtitle">Management System</p>
        </div>

        <?php if(isset($_GET['registered'])): ?>
            <div style="background: var(--color-success-soft); color: var(--color-success); padding: 1rem; border-radius: 6px; margin-bottom: 1rem; font-weight: 600; font-size: var(--text-sm); text-align: center;">
                Account created successfully! You can now log in.
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="login-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="login-form">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" style="width:100%" required>
            </div>
            <button type="submit" class="btn-accent" id="login-submit" style="width:100%; justify-content:center; padding: 0.625rem 1rem;">Sign in</button>
            
            <div style="text-align: center; margin-top: 1.5rem; font-size: var(--text-sm);">
                Resident without an account? <a href="register.php" style="color: var(--color-primary); font-weight: 600; text-decoration: none;">Register here</a>
            </div>
        </form>
    </div>
</body>
</html>
