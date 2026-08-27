<?php
session_start();

if (($_SESSION['role'] ?? '') === 'Admin') {
    header('Location: admin_dashboard.php');
    exit;
}

$loginError = $_SESSION['login_error'] ?? '';
$loginMessage = $_SESSION['login_message'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_message']);
$rememberedUser = htmlspecialchars($_COOKIE['remember_user'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.php">
    <title>Online Bookshop - Admin Login</title>
    <script>
    function collect_data() {
        let u = document.getElementById("username").value.trim();
        let p = document.getElementById("password").value.trim();
        let msg = "";
        if(u.length < 3) msg += "Username must be at least 3 characters\n";
        if(p.length < 5) msg += "Password must be at least 5 characters\n";
        if(msg) { alert(msg); return false; }
        return true;
    }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="main" style="max-width: 400px; margin-top: 80px;">
        <h2 style="margin-bottom: 20px; color: #245a45;">Admin Login</h2>
        
        <?php if ($loginError !== ''): ?>
            <div class="message error"><?php echo htmlspecialchars($loginError); ?></div>
        <?php endif; ?>
        <?php if ($loginMessage !== ''): ?>
            <div class="message"><?php echo htmlspecialchars($loginMessage); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="../Controller/AuthController.php" onsubmit="return collect_data()">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $rememberedUser; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="remember" name="remember" value="1"> Remember Me
                </label>
            </div>
            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>
        
        <p style="margin-top: 20px; text-align: center;">
            Default: admin / admin
        </p>
    </main>
</body>
</html>