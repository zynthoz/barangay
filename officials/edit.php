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
$query = "SELECT * FROM tbl_officials WHERE official_id = $id";
$result = mysqli_query($conn, $query);
$official = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $official_name = $_POST['official_name'];
    $position = $_POST['position'];
    $contact_information = $_POST['contact_information'];

    $update_query = "UPDATE tbl_officials SET official_name='$official_name', position='$position', contact_information='$contact_information' WHERE official_id=$id";
    mysqli_query($conn, $update_query);

    $action = "Edited official: " . $official_name;
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
    <title>BRMS — Edit Official</title>
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
            <span>Edit</span>
        </div>

        <div class="page-header">
            <h1>Edit Official</h1>
        </div>

        <form method="POST" id="edit-official-form">
            <div class="form-group">
                <label class="form-label" for="official_name">Full Name</label>
                <input type="text" id="official_name" name="official_name" class="form-control" style="width:100%" value="<?php echo $official['official_name']; ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="position">Position</label>
                <select id="position" name="position" class="form-select" style="width:100%" required>
                    <option value="Captain" <?php if($official['position']=='Captain') echo 'selected'; ?>>Captain</option>
                    <option value="Kagawad" <?php if($official['position']=='Kagawad') echo 'selected'; ?>>Kagawad</option>
                    <option value="Secretary" <?php if($official['position']=='Secretary') echo 'selected'; ?>>Secretary</option>
                    <option value="Treasurer" <?php if($official['position']=='Treasurer') echo 'selected'; ?>>Treasurer</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="contact_information">Contact</label>
                <input type="text" id="contact_information" name="contact_information" class="form-control" style="width:100%" value="<?php echo $official['contact_information']; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="update-official-btn">Update Official</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
