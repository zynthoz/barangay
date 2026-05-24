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
    $household_head = $_POST['household_head'];
    $address = $_POST['address'];
    $contact_information = $_POST['contact_information'];
    $number_of_members = $_POST['number_of_members'];

    $query = "INSERT INTO tbl_households (household_head, address, contact_information, number_of_members) VALUES ('$household_head', '$address', '$contact_information', $number_of_members)";
    mysqli_query($conn, $query);

    $action = "Added household: " . $household_head;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    header("Location: index.php");
    exit();
}

$current_page = 'households';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Add Household</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <a href="index.php">Households</a>
            <span class="sep">/</span>
            <span>Add</span>
        </div>

        <div class="page-header">
            <h1>Add Household</h1>
        </div>

        <form method="POST" id="add-household-form">
            <div class="form-group">
                <label class="form-label" for="household_head">Household Head</label>
                <input type="text" id="household_head" name="household_head" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_information">Contact</label>
                <input type="text" id="contact_information" name="contact_information" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="number_of_members">Number of Members</label>
                <input type="number" id="number_of_members" name="number_of_members" class="form-control" style="width:100%" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="save-household-btn">Save Household</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
