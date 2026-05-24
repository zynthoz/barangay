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
$query = "SELECT * FROM tbl_households WHERE household_head LIKE '%$search%' OR address LIKE '%$search%' ORDER BY household_id DESC";
$result = mysqli_query($conn, $query);

$current_page = 'households';
$base_path = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Households</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="page-shell page-content">
        <div class="breadcrumb-bar">
            <a href="../index.php">Dashboard</a>
            <span class="sep">/</span>
            <span>Households</span>
        </div>

        <div class="page-header">
            <h1>Households</h1>
            <a href="add.php" class="btn-accent" id="add-household-btn">+ Add Household</a>
        </div>

        <form method="GET" class="filter-bar" id="households-filter">
            <input type="text" name="search" class="form-control search-input" placeholder="Search by head or address…" value="<?php echo $search; ?>" id="households-search">
            <button type="submit" class="btn-secondary">Search</button>
        </form>

        <table class="data-table" id="households-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Household Head</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Members</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['household_id']; ?></td>
                    <td><?php echo $row['household_head']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['contact_information']; ?></td>
                    <td><?php echo $row['number_of_members']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['household_id']; ?>" class="btn-edit">Edit</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="6"><div class="empty-state"><p>No households found.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
