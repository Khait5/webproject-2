<?php
require_once __DIR__ . '/core/Session.php';
use Web\Core\Session;
Session::start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ColombianAge - L2J Mobius Interlude</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="dark-fantasy-theme">
    <nav class="navbar">
        <div class="nav-brand">ColombianAge</div>
        <div class="nav-links">
            <a href="modules/rankings/index.php">Rankings</a>
            <?php if (isset($_SESSION['account'])): ?>
                <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                    <a href="modules/admin/index.php" style="color: #e74c3c;">Admin CP</a>
                <?php endif; ?>
                <a href="modules/ucp/index.php">Control Panel</a>
                <a href="modules/auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="modules/auth/login.php">Login</a>
                <a href="modules/auth/register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-content">
            <h1>Welcome to ColombianAge</h1>
            <p>The ultimate Lineage II Interlude experience.</p>
            <?php if (!isset($_SESSION['account'])): ?>
                <a href="modules/auth/register.php" class="btn btn-large">Join Now</a>
            <?php else: ?>
                <a href="modules/ucp/index.php" class="btn btn-large">My Account</a>
            <?php endif; ?>
        </div>
    </header>

    <section class="server-status-section">
        <h2>Live Server Status</h2>
        <div class="status-grid" id="status-container">
            <div class="status-card">
                <h3>Login Server</h3>
                <div class="status-indicator" id="status-login"><span class="loading">Checking...</span></div>
            </div>
            <div class="status-card">
                <h3>Game Server</h3>
                <div class="status-indicator" id="status-game"><span class="loading">Checking...</span></div>
            </div>
            <div class="status-card">
                <h3>Database</h3>
                <div class="status-indicator" id="status-db"><span class="loading">Checking...</span></div>
            </div>
            <div class="status-card">
                <h3>Players Online</h3>
                <div class="counter" id="status-online">-</div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Powered by L2J Mobius.</p>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
