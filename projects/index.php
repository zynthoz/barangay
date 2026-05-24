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
$sort_status = isset($_GET['status']) ? $_GET['status'] : '';

$where_clauses = [];
if ($search) {
    $where_clauses[] = "(project_name LIKE '%$search%' OR project_description LIKE '%$search%')";
}
if ($sort_status) {
    $where_clauses[] = "project_status = '$sort_status'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "SELECT * FROM tbl_projects $where_sql ORDER BY start_date DESC";
$result = mysqli_query($conn, $query);

$current_page = 'projects';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Projects</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Projects</span>
        </div>

        <div class="page-header">
            <h1>Projects</h1>
            <a href="add.php" class="btn-accent" id="add-project-btn">+ Add Project</a>
        </div>

        <form method="GET" class="filter-bar" id="projects-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by name or description…" value="<?php echo $search; ?>" id="projects-search">
            <select name="status" class="form-select" id="projects-status-filter" style="max-width:160px">
                <option value="">All Statuses</option>
                <option value="Planned" <?php if($sort_status=='Planned') echo 'selected'; ?>>Planned</option>
                <option value="Ongoing" <?php if($sort_status=='Ongoing') echo 'selected'; ?>>Ongoing</option>
                <option value="Completed" <?php if($sort_status=='Completed') echo 'selected'; ?>>Completed</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>

        <table class="data-table" id="projects-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Project Name</th>
                    <th>Description</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                    $status_class = 'status--planned';
                    if ($row['project_status'] == 'Ongoing') $status_class = 'status--ongoing';
                    if ($row['project_status'] == 'Completed') $status_class = 'status--completed';
                ?>
                <tr>
                    <td><?php echo $row['project_id']; ?></td>
                    <td><?php echo $row['project_name']; ?></td>
                    <td><?php echo $row['project_description']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['start_date'])); ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['end_date'])); ?></td>
                    <td><span class="status <?php echo $status_class; ?>"><?php echo $row['project_status']; ?></span></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['project_id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="7"><div class="empty-state"><p>No projects found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
