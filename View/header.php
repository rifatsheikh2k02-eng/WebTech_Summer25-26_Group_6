<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="header">
    <h2>BookShop Admin Panel</h2>
    <div class="user-info">
        Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?>
        <a href="../Controller/AuthController.php?action=logout">Logout</a>
    </div>
</div>

<div class="navbar">
    <a href="admin_dashboard.php" class="<?php echo ($activePage ?? '') === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
    <a href="admin_dashboard.php?section=users" class="<?php echo ($activePage ?? '') === 'users' ? 'active' : ''; ?>">Users</a>
    <a href="admin_dashboard.php?section=settings" class="<?php echo ($activePage ?? '') === 'settings' ? 'active' : ''; ?>">Settings</a>
</div>