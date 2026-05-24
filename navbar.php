<?php
// Shared navigation bar
// Set $current_page before including this file, e.g. $current_page = 'residents';
// Set $base_path to '../' for subdirectory pages, '' for root pages

if (!isset($current_page)) $current_page = '';
if (!isset($base_path)) $base_path = '';
?>
<nav class="brms-nav" id="main-nav">
    <a class="brms-nav-brand" href="<?php echo $base_path; ?>index.php">
        BRMS
    </a>
    <ul class="brms-nav-links">
        <li><a href="<?php echo $base_path; ?>index.php" class="<?php if($current_page=='dashboard') echo 'active'; ?>">Dashboard</a></li>
        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Resident'): ?>
            <li><a href="<?php echo $base_path; ?>profile.php" class="<?php if($current_page=='profile') echo 'active'; ?>">My Profile</a></li>
            <li><a href="<?php echo $base_path; ?>incidents/add.php" class="<?php if($current_page=='report_incident') echo 'active'; ?>">Report Incident</a></li>
        <?php else: ?>
            <li><a href="<?php echo $base_path; ?>residents/index.php" class="<?php if($current_page=='residents') echo 'active'; ?>">Residents</a></li>
            <li><a href="<?php echo $base_path; ?>households/index.php" class="<?php if($current_page=='households') echo 'active'; ?>">Households</a></li>
            <li><a href="<?php echo $base_path; ?>officials/index.php" class="<?php if($current_page=='officials') echo 'active'; ?>">Officials</a></li>
            <li><a href="<?php echo $base_path; ?>incidents/index.php" class="<?php if($current_page=='incidents') echo 'active'; ?>">Incidents</a></li>
            <li><a href="<?php echo $base_path; ?>projects/index.php" class="<?php if($current_page=='projects') echo 'active'; ?>">Projects</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
            <li><a href="<?php echo $base_path; ?>users/index.php" class="<?php if($current_page=='users') echo 'active'; ?>">Users</a></li>
            <li><a href="<?php echo $base_path; ?>logs/index.php" class="<?php if($current_page=='logs') echo 'active'; ?>">Logs</a></li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
    <div class="brms-nav-right">
        <span class="brms-nav-user">
            <strong><?php echo $_SESSION['full_name']; ?></strong>
            <span><?php echo $_SESSION['role']; ?></span>
        </span>
        <a href="<?php echo $base_path; ?>logout.php" class="brms-nav-logout">Sign out</a>
    </div>
</nav>
