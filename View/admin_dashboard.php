<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION['role'] ?? '') !== 'Admin') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../Model/db.php';
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/OrderModel.php';

$conn = (new Database())->connect();
$userModel = new UserModel($conn);
$orderModel = new OrderModel($conn);

$totalUsers = count($userModel->getAllUsers());
$totalAdmins = 0;
$totalCustomers = 0;
$totalDeliverymen = 0;

foreach ($userModel->getAllUsers() as $user) {
    if ($user['role'] === 'Admin') $totalAdmins++;
    elseif ($user['role'] === 'Customer') $totalCustomers++;
    elseif ($user['role'] === 'Deliveryman') $totalDeliverymen++;
}

$profitRate = $userModel->getProfitRate();
$totalRevenue = $orderModel->getTotalRevenue();
$monthlyRevenue = $orderModel->getMonthlyRevenue();
$totalOrders = $orderModel->getTotalOrders();
$deliveredOrders = $orderModel->getDeliveredOrdersCount();

$adminName = htmlspecialchars($_SESSION['name'] ?? 'Admin');
$section = $_GET['section'] ?? 'dashboard';

if (!in_array($section, ['dashboard', 'users', 'settings'], true)) {
    $section = 'dashboard';
}

$message = $_SESSION['admin_message'] ?? '';
$error = $_SESSION['admin_error'] ?? '';
unset($_SESSION['admin_message'], $_SESSION['admin_error']);

$users = [];
if ($section === 'users') {
    $users = $userModel->getAllUsers();
}

$admin = [];
if ($section === 'settings') {
    $admin = $userModel->findById((int) $_SESSION['user_id']);
}

$activePage = $section;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.php">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="main">
        <?php if ($message !== ''): ?><div class="message"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error !== ''): ?><div class="message error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <?php if ($section === 'dashboard'): ?>
            <section class="welcome">
                <div><h1>Admin Dashboard</h1><p>Welcome back, <?php echo $adminName; ?>. Manage your BookShop users and settings.</p></div>
                <div class="welcome-badge">BS</div>
            </section>
            
            <div class="stats">
                <div class="stat"><span>Total Users</span><strong><?php echo $totalUsers; ?></strong></div>
                <div class="stat"><span>Total Revenue</span><strong>৳<?php echo number_format($totalRevenue, 2); ?></strong></div>
                <div class="stat"><span>This Month</span><strong>৳<?php echo number_format($monthlyRevenue, 2); ?></strong></div>
                <div class="stat"><span>Total Orders</span><strong><?php echo $totalOrders; ?></strong></div>
                <div class="stat"><span>Delivered</span><strong><?php echo $deliveredOrders; ?></strong></div>
                <div class="stat"><span>Profit Rate</span><strong><?php echo $profitRate; ?>%</strong></div>
            </div>
            
            <div class="quick-links">
                <a class="quick-link" href="admin_dashboard.php?section=users"><strong>Manage Users</strong><span>View, add, change roles, or delete users.</span></a>
                <a class="quick-link" href="admin_dashboard.php?section=settings"><strong>Settings & Account</strong><span>Edit profile, password, profit rate, or delete account.</span></a>
            </div>

        <?php elseif ($section === 'users'): ?>
            <section class="panel">
                <h2>User Management</h2>
                <?php if (!$users): ?><p>No users have been registered yet.</p><?php endif; ?>
                <?php foreach ($users as $user): ?>
                    <div class="user">
                        <div><strong><?php echo htmlspecialchars($user['name']); ?></strong><br><small>@<?php echo htmlspecialchars($user['username']); ?> - <?php echo htmlspecialchars($user['role']); ?></small></div>
                        <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']): ?>
                            <div class="user-actions">
                                <form method="POST" action="../Controller/AdminController.php">
                                    <input type="hidden" name="action" value="update_role"><input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
                                    <select name="role">
                                        <?php foreach (['Admin', 'Customer', 'Deliveryman'] as $role): ?><option value="<?php echo $role; ?>" <?php echo $user['role'] === $role ? 'selected' : ''; ?>><?php echo $role; ?></option><?php endforeach; ?>
                                    </select><button type="submit">Save Role</button>
                                </form>
                                <form method="POST" action="../Controller/AdminController.php" onsubmit="return confirm('Delete this user?');">
                                    <input type="hidden" name="action" value="delete_user"><input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>"><button class="danger" type="submit">Delete</button>
                                </form>
                            </div>
                        <?php else: ?><small>Current Admin</small><?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <form class="add-user" style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-top: 22px; padding-top: 20px; border-top: 1px solid #dbe5e0;" method="POST" action="../Controller/AdminController.php">
                    <div><h3>Add New User</h3><label for="new_name">Full Name</label><input id="new_name" name="name" required><label for="new_username">Username</label><input id="new_username" name="username" required></div>
                    <div><h3>&nbsp;</h3><label for="new_password">Password</label><input id="new_password" type="password" name="password" minlength="5" required><label for="new_role">Role</label><select id="new_role" name="role"><option>Customer</option><option>Deliveryman</option><option>Admin</option></select><input type="hidden" name="action" value="create_user"><button type="submit">Add User</button></div>
                </form>
            </section>

        <?php else: ?>
            <div class="settings-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                <section class="panel sub-panel">
                    <h2>System Settings</h2>
                    <form method="POST" action="../Controller/AdminController.php">
                        <input type="hidden" name="action" value="save_settings">
                        <label for="profit_rate">Profit Rate (%)</label>
                        <input id="profit_rate" type="number" name="profit_rate" min="0" max="100" step="0.01" value="<?php echo htmlspecialchars($profitRate); ?>" required>
                        <button type="submit">Save Profit Rate</button>
                    </form>
                </section>
                <section class="panel sub-panel">
                    <h2>Edit Profile</h2>
                    <div class="profile-info"><strong><?php echo htmlspecialchars($admin['name'] ?? 'Admin'); ?></strong><br>Username: <?php echo htmlspecialchars($admin['username'] ?? 'admin'); ?><br>Role: Admin</div>
                    <form method="POST" action="../Controller/AdminController.php"><input type="hidden" name="action" value="update_profile"><label for="profile_name">Full Name</label><input id="profile_name" name="name" value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>" required><label for="profile_username">Username</label><input id="profile_username" name="username" value="<?php echo htmlspecialchars($admin['username'] ?? ''); ?>" required><button type="submit">Save Profile</button></form>
                </section>
                <section class="panel sub-panel">
                    <h2>Change Password</h2>
                    <form method="POST" action="../Controller/AdminController.php"><input type="hidden" name="action" value="change_password"><label for="current_password">Current Password</label><input id="current_password" type="password" name="current_password" required><label for="new_account_password">New Password</label><input id="new_account_password" type="password" name="new_password" minlength="5" required><button type="submit">Change Password</button></form>
                </section>
                <section class="panel sub-panel">
                    <h2>Delete Profile</h2><p>This permanently removes your Admin account from the database.</p>
                    <form method="POST" action="../Controller/AdminController.php" onsubmit="return confirm('Delete your Admin profile permanently?');"><input type="hidden" name="action" value="delete_profile"><label for="delete_password">Confirm Password</label><input id="delete_password" type="password" name="password" required><button class="danger" type="submit">Delete Profile</button></form>
                </section>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>