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

$id = $_GET['id'];
$query = "SELECT * FROM tbl_residents WHERE resident_id = $id";
$result = mysqli_query($conn, $query);
$resident = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_name = $_POST['resident_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $contact_information = $_POST['contact_information'];
    $address = $_POST['address'];

    $update_query = "UPDATE tbl_residents SET resident_name='$resident_name', date_of_birth='$date_of_birth', gender='$gender', contact_information='$contact_information', address='$address' WHERE resident_id=$id";
    mysqli_query($conn, $update_query);

    $action = "Edited resident: " . $resident_name;
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
    <title>BRMS — Edit Resident</title>
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
            <span>Edit</span>
        </div>

        <div class="page-header">
            <h1>Edit Resident</h1>
        </div>

        <form method="POST" id="edit-resident-form">
            <div class="form-group">
                <label class="form-label" for="resident_name">Full Name</label>
                <input type="text" id="resident_name" name="resident_name" class="form-control" style="width:100%" value="<?php echo $resident['resident_name']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" style="width:100%" value="<?php echo $resident['date_of_birth']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="gender">Gender</label>
                <select id="gender" name="gender" class="form-select" style="width:100%" required>
                    <option value="Male" <?php if($resident['gender']=='Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if($resident['gender']=='Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_information">Contact</label>
                <input type="text" id="contact_information" name="contact_information" class="form-control" style="width:100%" value="<?php echo $resident['contact_information']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" style="width:100%" value="<?php echo $resident['address']; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="update-resident-btn">Update Resident</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
