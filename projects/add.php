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
    $project_name = $_POST['project_name'];
    $project_description = $_POST['project_description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $project_status = $_POST['project_status'];

    $query = "INSERT INTO tbl_projects (project_name, project_description, start_date, end_date, project_status) VALUES ('$project_name', '$project_description', '$start_date', '$end_date', '$project_status')";
    mysqli_query($conn, $query);

    $action = "Added project: " . $project_name;
    $log_query = "INSERT INTO tbl_logs (user_id, action, date_time) VALUES (" . $_SESSION['user_id'] . ", '$action', NOW())";
    mysqli_query($conn, $log_query);

    header("Location: index.php");
    exit();
}

$current_page = 'projects';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Add Project</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-shell--narrow page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <a href="index.php">Projects</a>
            <span class="sep">/</span>
            <span>Add</span>
        </div>

        <div class="page-header">
            <h1>Add Project</h1>
        </div>

        <form method="POST" id="add-project-form">
            <div class="form-group">
                <label class="form-label" for="project_name">Project Name</label>
                <input type="text" id="project_name" name="project_name" class="form-control" style="width:100%" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="project_description">Description</label>
                <textarea id="project_description" name="project_description" class="form-control" style="width:100%" required></textarea>
            </div>
            <div class="form-group" style="display:flex; gap: 1rem;">
                <div style="flex:1">
                    <label class="form-label" for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" style="width:100%" required>
                </div>
                <div style="flex:1">
                    <label class="form-label" for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" style="width:100%" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="project_status">Status</label>
                <select id="project_status" name="project_status" class="form-select" style="width:100%" required>
                    <option value="Planned">Planned</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent" id="save-project-btn">Save Project</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
