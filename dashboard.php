<!-- dashboard.php -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="dashboard-navbar">
    <div class="dashboard-brand">Fly KUET</div>
    <nav class="dashboard-nav">
        <ul>
            <li><a href="project.php" class="<?php echo ($current_page === 'project.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="diagram.php" class="<?php echo ($current_page === 'diagram.php') ? 'active' : ''; ?>">Diagram</a></li>
            <li><a href="view.php" class="<?php echo ($current_page === 'view.php') ? 'active' : ''; ?>">View Tables</a></li>
            <li><a href="query.php" class="<?php echo ($current_page === 'query.php') ? 'active' : ''; ?>">Run Query</a></li>
            <li><a href="customquery.php" class="<?php echo ($current_page === 'customquery.php') ? 'active' : ''; ?>">Custom Query</a></li>
            <li><a href="insert.php" class="<?php echo ($current_page === 'insert.php') ? 'active' : ''; ?>">Insert Data</a></li>
        </ul>
    </nav>
</header>
