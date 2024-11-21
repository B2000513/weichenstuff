<?php
// Get the current page name
$currentPage = basename($_SERVER['PHP_SELF']);
?>


<style>
    .logout {
        margin-top: 70px;
    }
</style>

<div class="sidebar">
    <div class="container">
    </div>
    <nav class="menu">
        <ul>
            <li> 
                <p class="title"> Wastex </p>
            </li>
            <li>
                <a href="admin_dashboard.php" class="<?php echo ($currentPage == 'admin_dashboard.php') ? 'active' : ''; ?>">
                    <i class="fa fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="admin_schedule.php" class="<?php echo ($currentPage == 'admin_schedule.php') ? 'active' : ''; ?>">
                    <i class="fa fa-calendar-check"></i><span>Schedule Pickup</span>
                </a>
            </li>
            <li>
                <a href="admin_report.php" class="<?php echo ($currentPage == 'admin_report.php') ? 'active' : ''; ?>">
                    <i class="fa fa-file"></i><span>Report</span>
                </a>
            </li>
            <li>
                <a href="admin_issue.php" class="<?php echo ($currentPage == 'admin_issue.php') ? 'active' : ''; ?>">
                    <i class="fa fa-file-text"></i><span>Manage Issue</span>
                </a>
            </li>
            <li>
                <a href="admin_announcement.php" class="<?php echo ($currentPage == 'admin_announcement.php') ? 'active' : ''; ?>">
                    <i class="fa fa-bell"></i><span>Announcement</span>
                </a>
            </li>
           
            <li class="logout">
                <a href="php/functions.php?op=signOut">
                    <i class="fa fa-sign-out"></i><span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
