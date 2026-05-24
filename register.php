<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // User Account Info
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Resident Profile Info
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact_information = mysqli_real_escape_string($conn, $_POST['contact_information']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Check if username exists
    $check_query = mysqli_query($conn, "SELECT * FROM tbl_users WHERE username = '$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $error = "Username is already taken. Please choose another.";
    } else {
        // 1. Create User Account
        $insert_user = "INSERT INTO tbl_users (full_name, role, username, password, email) 
                        VALUES ('$full_name', 'Resident', '$username', '$password', '$email')";
        
        if (mysqli_query($conn, $insert_user)) {
            $user_id = mysqli_insert_id($conn);
            
            // 2. Create Resident Profile linked to User Account
            $insert_resident = "INSERT INTO tbl_residents (resident_name, date_of_birth, gender, contact_information, address, user_id) 
                                VALUES ('$full_name', '$date_of_birth', '$gender', '$contact_information', '$address', $user_id)";
            mysqli_query($conn, $insert_resident);
            
            // Log the action
            $action = "New resident registered: " . $full_name;
            mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, date_time) VALUES ($user_id, '$action', NOW())");
            
            // Redirect to login
            header("Location: login.php?registered=true");
            exit();
        } else {
            $error = "Error creating account. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-box" style="max-width: 600px;">
        <div class="login-header">
            <h1 class="login-title">Resident Registration</h1>
            <p class="login-subtitle">Create your barangay account</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="login-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                
                <!-- Account Section -->
                <div style="grid-column: 1 / -1; margin-bottom: 0.5rem; margin-top: 1rem; font-weight: bold; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">Account Details</div>
                
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <!-- Profile Section -->
                <div style="grid-column: 1 / -1; margin-bottom: 0.5rem; margin-top: 1rem; font-weight: bold; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem;">Personal Details</div>
                
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="contact_information">Contact Number</label>
                    <input type="text" id="contact_information" name="contact_information" class="form-control" required>
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="address">Full Address</label>
                    <textarea id="address" name="address" class="form-control" required></textarea>
                </div>
            </div>
            
            <button type="submit" class="btn-accent" style="width:100%; justify-content:center; padding: 0.75rem 1rem; margin-top: 1rem; font-size: 1rem;">Create Account</button>
            
            <div style="text-align: center; margin-top: 1.5rem; font-size: var(--text-sm);">
                Already have an account? <a href="login.php" style="color: var(--color-primary); font-weight: 600; text-decoration: none;">Sign in here</a>
            </div>
        </form>
    </div>
</body>
</html>
