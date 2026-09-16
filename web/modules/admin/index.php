<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

if (!isset($_SESSION['account']) || !isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] <= 0) {
    header("Location: ../../index.php");
    exit;
}

$stats = [
    'accounts' => 0,
    'characters' => 0,
    'online' => 0,
    'clans' => 0
];

$conn = Database::getConnection();
if ($conn) {
    try {
        $stats['accounts'] = $conn->query("SELECT COUNT(*) FROM accounts")->fetchColumn();
        $stats['characters'] = $conn->query("SELECT COUNT(*) FROM characters")->fetchColumn();
        $stats['online'] = $conn->query("SELECT COUNT(*) FROM characters WHERE online = 1")->fetchColumn();
        $stats['clans'] = $conn->query("SELECT COUNT(*) FROM clan_data")->fetchColumn();
    } catch (\PDOException $e) {
        // Handle silently
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin CP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="admin-theme">
    <div class="page-wrapper">
        <!-- Left Sidebar: Admin Nav -->
        <aside class="sidebar-left">
            <div class="panel">
                <h2 class="panel-title">Admin Menu</h2>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php" style="border-color: #e74c3c;">Dashboard</a></li>
                        <li><a href="accounts.php">Manage Accounts</a></li>
                        <li><a href="characters.php">Manage Characters</a></li>
                        <li><a href="clans.php">Manage Clans</a></li>
                        <li><a href="punishments.php">Punishments</a></li>
                        <li><a href="../../index.php" style="color: var(--gold);">Back to Site</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
                    <h2 class="panel-title" style="margin-bottom: 0; border: none; padding: 0;">Dashboard Overview</h2>
                    <div>Logged in as: <strong style="color: #e74c3c;"><?php echo htmlspecialchars($_SESSION['account']); ?></strong></div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: space-between;">
                    <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; color: #888; font-size: 14px;">Total Accounts</h3>
                        <div style="font-size: 28px; font-weight: bold; color: var(--gold);"><?php echo number_format($stats['accounts']); ?></div>
                    </div>
                    <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; color: #888; font-size: 14px;">Total Characters</h3>
                        <div style="font-size: 28px; font-weight: bold; color: var(--gold);"><?php echo number_format($stats['characters']); ?></div>
                    </div>
                    <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid #27ae60;">
                        <h3 style="margin-top: 0; color: #888; font-size: 14px;">Players Online</h3>
                        <div style="font-size: 28px; font-weight: bold; color: #27ae60;"><?php echo number_format($stats['online']); ?></div>
                    </div>
                    <div style="flex: 1; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid var(--border-color);">
                        <h3 style="margin-top: 0; color: #888; font-size: 14px;">Total Clans</h3>
                        <div style="font-size: 28px; font-weight: bold; color: var(--gold);"><?php echo number_format($stats['clans']); ?></div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Admin Control Panel.</p>
    </footer>
</body>
</html>