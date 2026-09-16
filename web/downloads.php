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
    <title>Downloads - ColombianAge</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- Left Sidebar: Navigation -->
        <aside class="sidebar-left">
            <div class="panel">
                <h2 class="panel-title">Main Menu</h2>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="modules/auth/register.php">Register</a></li>
                        <li><a href="downloads.php">Downloads</a></li>
                        <li><a href="modules/rankings/index.php">Rankings</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <h2 class="panel-title">Downloads</h2>

                <div style="background: rgba(255,255,255,0.05); padding: 20px; border: 1px solid var(--border-color); border-radius: 4px; margin-bottom: 20px;">
                    <h3 style="color: var(--gold); margin-top: 0;">System Patch v1.0</h3>
                    <p style="margin-bottom: 15px;">Download our custom system patch to connect to the ColombianAge server. This patch includes all necessary files, custom textures, and connection settings.</p>
                    <p><strong>Size:</strong> 45 MB</p>
                    <p><strong>Date:</strong> <?php echo date('Y-m-d'); ?></p>

                    <a href="#" class="btn" style="display: inline-block; margin-top: 10px;">Download Patch (Mega)</a>
                    <a href="#" class="btn btn-secondary" style="display: inline-block; margin-top: 10px; margin-left: 10px;">Download Patch (MediaFire)</a>
                </div>

                <div style="background: rgba(255,255,255,0.05); padding: 20px; border: 1px solid var(--border-color); border-radius: 4px;">
                    <h3 style="color: var(--gold); margin-top: 0;">Lineage II Interlude Client</h3>
                    <p style="margin-bottom: 15px;">If you don't have the original Lineage II Interlude client, you can download a clean version here.</p>
                    <p><strong>Size:</strong> 3.2 GB</p>

                    <a href="#" class="btn" style="display: inline-block; margin-top: 10px;">Download Client</a>
                </div>
            </div>
        </main>

        <!-- Right Sidebar: Auth & Server Status -->
        <aside class="sidebar-right">
            <div class="panel">
                <h2 class="panel-title">Account</h2>
                <?php if (!isset($_SESSION['account'])): ?>
                    <form action="modules/auth/login.php" method="POST" class="auth-form">
                        <input type="text" name="login" placeholder="Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" class="btn">Login</button>
                    </form>
                    <div style="text-align:center; margin-top:10px;">
                        <a href="modules/auth/register.php" style="font-size: 12px;">Create Account</a>
                    </div>
                <?php else: ?>
                    <div style="text-align:center;">
                        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['account']); ?></strong>!</p>
                        <div class="auth-form" style="margin-top:15px;">
                            <a href="modules/ucp/index.php" class="btn btn-secondary">User Panel</a>
                            <a href="modules/auth/logout.php" class="btn">Logout</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Powered by L2J Mobius.</p>
    </footer>
</body>
</html>