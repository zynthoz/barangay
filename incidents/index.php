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

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort_type = isset($_GET['type']) ? $_GET['type'] : '';

$where_clauses = [];
if ($search) {
    $where_clauses[] = "(res.resident_name LIKE '%$search%' OR o.official_name LIKE '%$search%' OR r.incident_details LIKE '%$search%')";
}
if ($sort_type) {
    $where_clauses[] = "r.incident_type = '$sort_type'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "SELECT r.report_id, res.resident_name, o.official_name, r.incident_type, r.incident_details, r.date_reported 
          FROM tbl_incident_reports r 
          JOIN tbl_residents res ON r.resident_id = res.resident_id 
          JOIN tbl_officials o ON r.official_id = o.official_id 
          $where_sql 
          ORDER BY r.date_reported DESC";
$result = mysqli_query($conn, $query);

$current_page = 'incidents';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Incidents</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Incidents</span>
        </div>

        <div class="page-header">
            <h1>Incident Reports</h1>
            <a href="add.php" class="btn-accent" id="add-incident-btn">+ Add Report</a>
        </div>

        <form method="GET" class="filter-bar" id="incidents-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by resident, official, or details…" value="<?php echo $search; ?>" id="incidents-search">
            <select name="type" class="form-select" id="incidents-type-filter" style="max-width:180px">
                <option value="">All Types</option>
                <option value="Theft" <?php if($sort_type=='Theft') echo 'selected'; ?>>Theft</option>
                <option value="Assault" <?php if($sort_type=='Assault') echo 'selected'; ?>>Assault</option>
                <option value="Noise Complaint" <?php if($sort_type=='Noise Complaint') echo 'selected'; ?>>Noise Complaint</option>
                <option value="Other" <?php if($sort_type=='Other') echo 'selected'; ?>>Other</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>

        <table class="data-table" id="incidents-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Reporting Official</th>
                    <th>Type</th>
                    <th>Details</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['report_id']; ?></td>
                    <td><?php echo $row['resident_name']; ?></td>
                    <td><?php echo $row['official_name']; ?></td>
                    <td><?php echo $row['incident_type']; ?></td>
                    <td><?php echo $row['incident_details']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['date_reported'])); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['report_id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="7"><div class="empty-state"><p>No incident reports found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
