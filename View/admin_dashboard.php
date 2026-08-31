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
require_once __DIR__ . '/../Model/BookModel.php';

$conn = (new Database())->connect();
$userModel = new UserModel($conn);
$orderModel = new OrderModel($conn);
$bookModel = new BookModel($conn);

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

$totalBooks = $bookModel->getTotalBooks();
$totalStockValue = $bookModel->getTotalStockValue();

$adminName = htmlspecialchars($_SESSION['name'] ?? 'Admin');
$section = $_GET['section'] ?? 'dashboard';

if (!in_array($section, ['dashboard', 'books', 'users', 'settings'], true)) {
    $section = 'dashboard';
}

$message = $_SESSION['admin_message'] ?? '';
$error = $_SESSION['admin_error'] ?? '';
unset($_SESSION['admin_message'], $_SESSION['admin_error']);

$users = [];
if ($section === 'users') {
    $users = $userModel->getAllUsers();
}

$books = [];
if ($section === 'books') {
    $books = $bookModel->getAllBooks();
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
                <a class="quick-link" href="admin_dashboard.php?section=books"><strong>Manage Books</strong><span>View, add, edit, or delete books.</span></a>
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

        <?php elseif ($section === 'books'): ?>
            <section class="panel">
                <h2>Book Management</h2>
                <?php if (!$books): ?><p>No books have been added yet.</p><?php endif; ?>
                <?php foreach ($books as $book): ?>
                    <div class="user">
                        <div><strong><?php echo htmlspecialchars($book['title']); ?></strong><br><small>by <?php echo htmlspecialchars($book['author']); ?> - <?php echo htmlspecialchars($book['category'] ?? 'N/A'); ?> - ৳<?php echo number_format($book['price'], 2); ?> - Stock: <?php echo (int) $book['stock']; ?></small></div>
                        <div class="user-actions">
                            <form method="POST" action="../Controller/AdminController.php" style="display: inline;">
                                <input type="hidden" name="action" value="update_book">
                                <input type="hidden" name="book_id" value="<?php echo (int) $book['id']; ?>">
                                <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required style="width: 150px;">
                                <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required style="width: 120px;">
                                <input type="text" name="category" value="<?php echo htmlspecialchars($book['category'] ?? ''); ?>" style="width: 100px;">
                                <input type="number" name="price" step="0.01" value="<?php echo $book['price']; ?>" required style="width: 80px;">
                                <input type="number" name="stock" value="<?php echo (int) $book['stock']; ?>" required style="width: 60px;">
                                <button type="submit">Save</button>
                            </form>
                            <form method="POST" action="../Controller/AdminController.php" onsubmit="return confirm('Delete this book?');" style="display: inline;">
                                <input type="hidden" name="action" value="delete_book"><input type="hidden" name="book_id" value="<?php echo (int) $book['id']; ?>"><button class="danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <form class="add-user" style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-top: 22px; padding-top: 20px; border-top: 1px solid #dbe5e0;" method="POST" action="../Controller/AdminController.php">
                    <div><h3>Add New Book</h3><label for="new_title">Title</label><input id="new_title" name="title" required><label for="new_author">Author</label><input id="new_author" name="author" required><label for="new_category">Category</label><input id="new_category" name="category"></div>
                    <div><h3>&nbsp;</h3><label for="new_price">Price (৳)</label><input id="new_price" type="number" name="price" step="0.01" min="0" required><label for="new_stock">Stock</label><input id="new_stock" type="number" name="stock" min="0" required><input type="hidden" name="action" value="create_book"><button type="submit">Add Book</button></div>
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