<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Resident') {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM tbl_residents WHERE user_id = $user_id");
$resident = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_name = $_POST['resident_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact_information = $_POST['contact_information'];
    $address = $_POST['address'];
    $new_password = $_POST['new_password'];

    if ($resident) {
        $update = "UPDATE tbl_residents SET 
                    resident_name='$resident_name', 
                    date_of_birth='$date_of_birth', 
                    gender='$gender', 
                    contact_information='$contact_information', 
                    address='$address' 
                   WHERE user_id=$user_id";
        mysqli_query($conn, $update);
    } else {
        $insert = "INSERT INTO tbl_residents (resident_name, date_of_birth, gender, contact_information, address, user_id) 
                   VALUES ('$resident_name', '$date_of_birth', '$gender', '$contact_information', '$address', $user_id)";
        mysqli_query($conn, $insert);
    }
    
    // Log action
    $action = $resident ? 'Updated their resident profile' : 'Created their resident profile';
    mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, date_time) VALUES ($user_id, '$action', NOW())");
    
    // Update password if provided
    if (!empty($new_password)) {
        $safe_password = mysqli_real_escape_string($conn, $new_password);
        mysqli_query($conn, "UPDATE tbl_users SET password='$safe_password' WHERE user_id=$user_id");
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, date_time) VALUES ($user_id, 'Changed their password', NOW())");
    }
    
    header("Location: profile.php?success=1");
    exit();
}

$current_page = 'profile';
$base_path = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="page-shell page-shell--narrow page-content">
        <div class="page-header">
            <h1>My Resident Profile</h1>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div style="background: var(--color-success-soft); color: var(--color-success); padding: 1rem; border-radius: 6px; margin-bottom: 1rem; font-weight: 600;">
                Profile saved successfully!
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label" for="resident_name">Full Name</label>
                <input type="text" id="resident_name" name="resident_name" class="form-control" value="<?php echo $resident ? htmlspecialchars($resident['resident_name']) : htmlspecialchars($_SESSION['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $resident ? $resident['date_of_birth'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select id="gender" name="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?php if($resident && $resident['gender']=='Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($resident && $resident['gender']=='Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_information">Contact Information</label>
                <input type="text" id="contact_information" name="contact_information" class="form-control" value="<?php echo $resident ? htmlspecialchars($resident['contact_information']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Address</label>
                <textarea id="address" name="address" class="form-control" required><?php echo $resident ? htmlspecialchars($resident['address']) : ''; ?></textarea>
            </div>
            
            <div style="margin-top: 2rem; font-weight: bold; border-bottom: 1px solid var(--color-border); padding-bottom: 0.5rem; margin-bottom: 1rem;">Change Password</div>
            <div class="form-group">
                <label class="form-label" for="new_password">New Password (leave blank to keep current)</label>
                <input type="password" id="new_password" name="new_password" class="form-control">
            </div>
            
            <div style="display:flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-accent">Save Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
