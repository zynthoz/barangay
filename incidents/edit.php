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
$query = "SELECT * FROM tbl_incident_reports WHERE report_id = $id";
$result = mysqli_query($conn, $query);
$incident = mysqli_fetch_assoc($result);

$res_query = "SELECT resident_id, resident_name FROM tbl_residents";
$res_result = mysqli_query($conn, $res_query);

$off_query = "SELECT official_id, official_name FROM tbl_officials";
$off_result = mysqli_query($conn, $off_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_POST['resident_id'];
    $official_id = $_POST['official_id'];
    $incident_type = $_POST['incident_type'];
    $incident_details = $_POST['incident_details'];
    $date_reported = $_POST['date_reported'];

    $update_query = "UPDATE tbl_incident_reports SET resident_id=$resident_id, official_id=$official_id, incident_type='$incident_type', incident_details='$incident_details', date_reported='$date_reported' WHERE report_id=$id";
    mysqli_query($conn, $update_query);

    $action = "Edited incident report: " . $incident_type;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    header("Location: index.php");
    exit();
}

$current_page = 'incidents';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Edit Incident</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <a href="index.php">Incidents</a>
            <span class="sep">/</span>
            <span>Edit</span>
        </div>

        <div class="page-header">
            <h1>Edit Incident Report</h1>
        </div>

        <form method="POST" id="edit-incident-form">
            <div class="form-group">
                <label class="form-label" for="resident_id">Resident Involved</label>
                <select id="resident_id" name="resident_id" class="form-select" style="width:100%" required>
                    <?php while($row = mysqli_fetch_assoc($res_result)): ?>
                        <option value="<?php echo $row['resident_id']; ?>" <?php if($incident['resident_id'] == $row['resident_id']) echo 'selected'; ?>><?php echo $row['resident_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="official_id">Reporting Official</label>
                <select id="official_id" name="official_id" class="form-select" style="width:100%" required>
                    <?php while($row = mysqli_fetch_assoc($off_result)): ?>
                        <option value="<?php echo $row['official_id']; ?>" <?php if($incident['official_id'] == $row['official_id']) echo 'selected'; ?>><?php echo $row['official_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="incident_type">Type</label>
                <select id="incident_type" name="incident_type" class="form-select" style="width:100%" required>
                    <option value="Theft" <?php if($incident['incident_type']=='Theft') echo 'selected'; ?>>Theft</option>
                    <option value="Assault" <?php if($incident['incident_type']=='Assault') echo 'selected'; ?>>Assault</option>
                    <option value="Noise Complaint" <?php if($incident['incident_type']=='Noise Complaint') echo 'selected'; ?>>Noise Complaint</option>
                    <option value="Other" <?php if($incident['incident_type']=='Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="incident_details">Details</label>
                <textarea id="incident_details" name="incident_details" class="form-control" style="width:100%" required><?php echo $incident['incident_details']; ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="date_reported">Date Reported</label>
                <input type="date" id="date_reported" name="date_reported" class="form-control" style="width:100%" value="<?php echo $incident['date_reported']; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="update-incident-btn">Update Report</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
