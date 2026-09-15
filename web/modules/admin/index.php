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
    <title>Admin CP - Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-theme">
    <aside class="admin-sidebar">
        <div class="admin-brand">L2 Admin CP</div>
        <nav class="admin-nav">
            <a href="index.php" class="active">Dashboard</a>
            <a href="accounts.php">Accounts</a>
            <a href="characters.php">Characters</a>
            <a href="clans.php">Clans</a>
            <a href="punishments.php">Punishments</a>
            <a href="../../index.php" class="back-link">← Back to Site</a>
        </nav>
    </aside>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Dashboard Overview</h1>
            <div>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['account']); ?></strong></div>
        </header>

        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <h3>Total Accounts</h3>
                <div class="value"><?php echo number_format($stats['accounts']); ?></div>
            </div>
            <div class="admin-stat-card">
                <h3>Total Characters</h3>
                <div class="value"><?php echo number_format($stats['characters']); ?></div>
            </div>
            <div class="admin-stat-card">
                <h3>Players Online</h3>
                <div class="value text-success"><?php echo number_format($stats['online']); ?></div>
            </div>
            <div class="admin-stat-card">
                <h3>Total Clans</h3>
                <div class="value"><?php echo number_format($stats['clans']); ?></div>
            </div>
        </div>
    </main>
</body>
</html>
