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
$sort_position = isset($_GET['position']) ? $_GET['position'] : '';

$where_clauses = [];
if ($search) {
    $where_clauses[] = "(official_name LIKE '%$search%')";
}
if ($sort_position) {
    $where_clauses[] = "position = '$sort_position'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "SELECT * FROM tbl_officials $where_sql ORDER BY official_id DESC";
$result = mysqli_query($conn, $query);

$current_page = 'officials';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Officials</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Officials</span>
        </div>

        <div class="page-header">
            <h1>Officials</h1>
            <a href="add.php" class="btn-accent" id="add-official-btn">+ Add Official</a>
        </div>

        <form method="GET" class="filter-bar" id="officials-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by name…" value="<?php echo $search; ?>" id="officials-search">
            <select name="position" class="form-select" id="officials-position-filter" style="max-width:160px">
                <option value="">All Positions</option>
                <option value="Captain" <?php if($sort_position=='Captain') echo 'selected'; ?>>Captain</option>
                <option value="Kagawad" <?php if($sort_position=='Kagawad') echo 'selected'; ?>>Kagawad</option>
                <option value="Secretary" <?php if($sort_position=='Secretary') echo 'selected'; ?>>Secretary</option>
                <option value="Treasurer" <?php if($sort_position=='Treasurer') echo 'selected'; ?>>Treasurer</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>

        <table class="data-table" id="officials-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['official_id']; ?></td>
                    <td><?php echo $row['official_name']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['contact_information']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['official_id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="5"><div class="empty-state"><p>No officials found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
