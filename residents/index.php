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
$sort_gender = isset($_GET['gender']) ? $_GET['gender'] : '';

$where_clauses = [];
if ($search) {
    $where_clauses[] = "(resident_name LIKE '%$search%' OR address LIKE '%$search%')";
}
if ($sort_gender) {
    $where_clauses[] = "gender = '$sort_gender'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "SELECT * FROM tbl_residents $where_sql ORDER BY resident_id DESC";
$result = mysqli_query($conn, $query);

$current_page = 'residents';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Residents</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Residents</span>
        </div>

        <div class="page-header">
            <h1>Residents</h1>
            <a href="add.php" class="btn-accent" id="add-resident-btn">+ Add Resident</a>
        </div>

        <form method="GET" class="filter-bar" id="residents-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by name or address…" value="<?php echo $search; ?>" id="residents-search">
            <select name="gender" class="form-select" id="residents-gender-filter" style="max-width:160px">
                <option value="">All Genders</option>
                <option value="Male" <?php if($sort_gender=='Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($sort_gender=='Female') echo 'selected'; ?>>Female</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>

        <table class="data-table" id="residents-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['resident_id']; ?></td>
                    <td><?php echo $row['resident_name']; ?></td>
                    <td><?php echo date('M j, Y', strtotime($row['date_of_birth'])); ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['contact_information']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['resident_id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="7"><div class="empty-state"><p>No residents found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
