<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../db.php';
if ($_SESSION['role'] === 'Resident') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_name = $_POST['resident_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact_information = $_POST['contact_information'];
    $address = $_POST['address'];

    $query = "INSERT INTO tbl_residents (resident_name, date_of_birth, gender, contact_information, address) VALUES ('$resident_name', '$date_of_birth', '$gender', '$contact_information', '$address')";
    mysqli_query($conn, $query);

    $action = "Added resident: " . $resident_name;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    header("Location: index.php");
    exit();
}

$current_page = 'residents';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Add Resident</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <a href="index.php">Residents</a>
            <span class="sep">/</span>
            <span>Add</span>
        </div>

        <div class="page-header">
            <h1>Add Resident</h1>
        </div>

        <form method="POST" id="add-resident-form">
            <div class="form-group">
                <label class="form-label" for="resident_name">Full Name</label>
                <input type="text" id="resident_name" name="resident_name" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select id="gender" name="gender" class="form-select" style="width:100%" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_information">Contact</label>
                <input type="text" id="contact_information" name="contact_information" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" style="width:100%" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="save-resident-btn">Save Resident</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
