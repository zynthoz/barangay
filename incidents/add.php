<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$is_resident = ($_SESSION['role'] === 'Resident');
$user_id = $_SESSION['user_id'];
$resident_profile = null;

if ($is_resident) {
    $q = mysqli_query($conn, "SELECT resident_id FROM tbl_residents WHERE user_id = $user_id");
    $resident_profile = mysqli_fetch_assoc($q);
}

$res_query = "SELECT resident_id, resident_name FROM tbl_residents";
$res_result = mysqli_query($conn, $res_query);

$off_query = "SELECT official_id, official_name FROM tbl_officials";
$off_result = mysqli_query($conn, $off_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($is_resident) {
        if (!$resident_profile) {
            die("Please set up your Resident Profile first before reporting an incident.");
        }
        $resident_id = $resident_profile['resident_id'];
        $official_id = "NULL"; // Self-reported
    } else {
        $resident_id = $_POST['resident_id'];
        $official_id = $_POST['official_id'];
    }
    
    $incident_type = $_POST['incident_type'];
    $incident_details = $_POST['incident_details'];
    $date_reported = $_POST['date_reported'];

    $query = "INSERT INTO tbl_incident_reports (resident_id, official_id, incident_type, incident_details, date_reported) VALUES ($resident_id, $official_id, '$incident_type', '$incident_details', '$date_reported')";
    mysqli_query($conn, $query);

    $action = "Added incident report: " . $incident_type;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    if ($is_resident) {
        header("Location: ../index.php"); // Residents go back to dashboard
    } else {
        header("Location: index.php");
    }
    exit();
}

// Adjust breadcrumbs/nav for Resident
$current_page = $is_resident ? 'report_incident' : 'incidents';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Add Incident</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <?php if (!$is_resident): ?>
                <a href="index.php">Incidents</a>
                <span class="sep">/</span>
            <?php endif; ?>
            <span>Report Incident</span>
        </div>

        <div class="page-header">
            <h1>Report an Incident</h1>
        </div>
        
        <?php if ($is_resident && !$resident_profile): ?>
            <div style="background: var(--color-warning-soft); color: var(--color-warning); padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                <strong>Notice:</strong> Please <a href="../profile.php" style="color:var(--color-warning); text-decoration:underline;">complete your Resident Profile</a> before you can submit an incident report.
            </div>
        <?php else: ?>
            <form method="POST" id="add-incident-form">
                <?php if (!$is_resident): ?>
                <div class="form-group">
                    <label class="form-label" for="resident_id">Resident Involved</label>
                    <select id="resident_id" name="resident_id" class="form-select" style="width:100%" required>
                        <option value="">Select Resident</option>
                        <?php while($row = mysqli_fetch_assoc($res_result)): ?>
                            <option value="<?php echo $row['resident_id']; ?>"><?php echo $row['resident_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="official_id">Reporting Official</label>
                    <select id="official_id" name="official_id" class="form-select" style="width:100%" required>
                        <option value="">Select Official</option>
                        <?php while($row = mysqli_fetch_assoc($off_result)): ?>
                            <option value="<?php echo $row['official_id']; ?>"><?php echo $row['official_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label" for="incident_type">Type</label>
                    <select id="incident_type" name="incident_type" class="form-select" style="width:100%" required>
                        <option value="Theft">Theft</option>
                        <option value="Assault">Assault</option>
                        <option value="Noise Complaint">Noise Complaint</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="incident_details">Details</label>
                    <textarea id="incident_details" name="incident_details" class="form-control" style="width:100%" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="date_reported">Date Reported</label>
                    <input type="date" id="date_reported" name="date_reported" class="form-control" style="width:100%" value="<?php echo date('Y-m-d'); ?>" required <?php if($is_resident) echo 'readonly'; ?>>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-accent" id="save-incident-btn">Submit Report</button>
                    <a href="<?php echo $is_resident ? '../index.php' : 'index.php'; ?>" class="btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
