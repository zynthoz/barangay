<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$is_resident = ($_SESSION['role'] === 'Resident');

if (!$is_resident) {
    // Dashboard counts
    $query1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_residents");
    $row1 = mysqli_fetch_assoc($query1);
    $total_residents = $row1['total'];

    $query2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_households");
    $row2 = mysqli_fetch_assoc($query2);
    $total_households = $row2['total'];

    $query3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_officials");
    $row3 = mysqli_fetch_assoc($query3);
    $total_officials = $row3['total'];

    $query4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_incident_reports");
    $row4 = mysqli_fetch_assoc($query4);
    $total_incidents = $row4['total'];

    $query5 = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_projects");
    $row5 = mysqli_fetch_assoc($query5);
    $total_projects = $row5['total'];

    // Recent activity (last 5 logs)
    $get_logs = mysqli_query($conn, "SELECT l.action, l.date_time, u.full_name 
                  FROM tbl_logs l 
                  JOIN tbl_users u ON l.user_id = u.user_id 
                  ORDER BY l.date_time DESC LIMIT 5");
} else {
    // News feed for residents
    $projects_query = mysqli_query($conn, "SELECT project_name as title, project_description as details, start_date as date, 'Project' as type FROM tbl_projects ORDER BY start_date DESC LIMIT 5");
    $incidents_query = mysqli_query($conn, "SELECT incident_type as title, incident_details as details, date_reported as date, 'Incident' as type FROM tbl_incident_reports ORDER BY date_reported DESC LIMIT 5");
    
    $news_feed = [];
    while($row = mysqli_fetch_assoc($projects_query)) { $news_feed[] = $row; }
    while($row = mysqli_fetch_assoc($incidents_query)) { $news_feed[] = $row; }
    
    usort($news_feed, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}

// Greeting based on time
$current_time = date('G');
if ($current_time < 12) {
    $msg = "Good morning";
} elseif ($current_time < 18) {
    $msg = "Good afternoon";
} else {
    $msg = "Good evening";
}

$current_page = 'dashboard';
$base_path = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRMS — Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="page-shell page-content">
        
        <div class="dashboard-hero">
            <h1 class="greeting"><?php echo $msg; ?>, <?php echo explode(' ', trim($_SESSION['full_name']))[0]; ?>!</h1>
            <p class="greeting-sub">Here's a quick overview of what's happening in your barangay today.</p>
        </div>

        <?php if (!$is_resident): ?>
        <div class="dash-grid">
            <a href="residents/index.php" class="dash-tile tile-residents" id="tile-residents">
                <div class="dash-tile-label">Total Residents</div>
                <div class="dash-tile-count"><?php echo number_format($total_residents); ?></div>
                <span class="dash-tile-link">View directory &rarr;</span>
            </a>
            
            <a href="households/index.php" class="dash-tile tile-households" id="tile-households">
                <div class="dash-tile-label">Households</div>
                <div class="dash-tile-count"><?php echo number_format($total_households); ?></div>
                <span class="dash-tile-link">View households &rarr;</span>
            </a>
            
            <a href="officials/index.php" class="dash-tile tile-officials" id="tile-officials">
                <div class="dash-tile-label">Barangay Officials</div>
                <div class="dash-tile-count"><?php echo number_format($total_officials); ?></div>
                <span class="dash-tile-link">View roster &rarr;</span>
            </a>
            
            <a href="incidents/index.php" class="dash-tile tile-incidents" id="tile-incidents">
                <div class="dash-tile-label">Incident Reports</div>
                <div class="dash-tile-count"><?php echo number_format($total_incidents); ?></div>
                <span class="dash-tile-link">View records &rarr;</span>
            </a>
            
            <a href="projects/index.php" class="dash-tile tile-projects" id="tile-projects">
                <div class="dash-tile-label">Community Projects</div>
                <div class="dash-tile-count"><?php echo number_format($total_projects); ?></div>
                <span class="dash-tile-link">View status &rarr;</span>
            </a>
        </div>

        <div class="activity-card">
            <h3 class="dash-section-title">
                Recent Activity
            </h3>
            
            <?php if (mysqli_num_rows($get_logs) > 0): ?>
            <ul class="activity-list">
                <?php while ($log_row = mysqli_fetch_assoc($get_logs)): ?>
                <li class="activity-item">
                    <span class="activity-dot"></span>
                    <div class="activity-text">
                        <strong><?php echo htmlspecialchars($log_row['full_name']); ?></strong><br>
                        <?php echo htmlspecialchars($log_row['action']); ?>
                    </div>
                    <span class="activity-meta"><?php echo date('M j, g:ia', strtotime($log_row['date_time'])); ?></span>
                </li>
                <?php endwhile; ?>
            </ul>
            <?php else: ?>
            <div class="empty-state">
                <p>No recent activity yet. Actions will appear here as records are added or edited.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php else: ?>
        <div class="activity-card">
            <h3 class="dash-section-title">Community News & Updates</h3>
            
            <?php if (count($news_feed) > 0): ?>
            <ul class="activity-list">
                <?php foreach ($news_feed as $item): ?>
                <li class="activity-item">
                    <span class="activity-dot" style="<?php if($item['type'] == 'Incident') echo 'background-color: var(--color-danger-soft); color: var(--color-danger);'; else echo 'background-color: var(--color-accent-light); color: var(--color-accent);'; ?>"></span>
                    <div class="activity-text">
                        <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                        <span class="status status--<?php echo $item['type'] == 'Project' ? 'ongoing' : 'planned'; ?>" style="font-size: 0.65rem; margin-left: 8px;"><?php echo $item['type']; ?></span><br>
                        <?php echo htmlspecialchars($item['details']); ?>
                    </div>
                    <span class="activity-meta"><?php echo date('M j, Y', strtotime($item['date'])); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <div class="empty-state">
                <p>No community news at this time.</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
    </div>
</body>
</html>
