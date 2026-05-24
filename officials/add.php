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
    $official_name = $_POST['official_name'];
    $position = $_POST['position'];
    $contact_information = $_POST['contact_information'];

    $query = "INSERT INTO tbl_officials (official_name, position, contact_information) VALUES ('$official_name', '$position', '$contact_information')";
    mysqli_query($conn, $query);

    $action = "Added official: " . $official_name;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    header("Location: index.php");
    exit();
}

$current_page = 'officials';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Add Official</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <a href="index.php">Officials</a>
            <span class="sep">/</span>
            <span>Add</span>
        </div>

        <div class="page-header">
            <h1>Add Official</h1>
        </div>

        <form method="POST" id="add-official-form">
            <div class="form-group">
                <label class="form-label" for="official_name">Full Name</label>
                <input type="text" id="official_name" name="official_name" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="position">Position</label>
                <select id="position" name="position" class="form-select" style="width:100%" required>
                    <option value="Captain">Captain</option>
                    <option value="Kagawad">Kagawad</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Treasurer">Treasurer</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_information">Contact</label>
                <input type="text" id="contact_information" name="contact_information" class="form-control" style="width:100%" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="save-official-btn">Save Official</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
