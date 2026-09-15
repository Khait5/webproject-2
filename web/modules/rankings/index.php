<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

$topPvp = [];
$topPk = [];
$castles = [];
$error = '';

$conn = Database::getConnection();
if ($conn) {
    try {
        // Fetch Top PvP (excluding admins)
        $stmtPvp = $conn->query("SELECT char_name, level, pvpkills, clanid FROM characters WHERE accesslevel = 0 ORDER BY pvpkills DESC LIMIT 10");
        if ($stmtPvp) $topPvp = $stmtPvp->fetchAll();

        // Fetch Top PK (excluding admins)
        $stmtPk = $conn->query("SELECT char_name, level, pkkills, clanid FROM characters WHERE accesslevel = 0 ORDER BY pkkills DESC LIMIT 10");
        if ($stmtPk) $topPk = $stmtPk->fetchAll();

        // Fetch Castles
        $stmtCastle = $conn->query("SELECT name, taxPercent, siegeDate FROM castle ORDER BY id ASC");
        if ($stmtCastle) $castles = $stmtCastle->fetchAll();

    } catch (\PDOException $e) {
        $error = "Failed to load rankings.";
    }
} else {
    $error = "Database connection failed.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ColombianAge - Rankings</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="dark-fantasy-theme">
    <nav class="navbar">
        <div class="nav-brand">ColombianAge</div>
        <div class="nav-links">
            <a href="../../index.php">Home</a>
            <?php if (isset($_SESSION['account'])): ?>
                <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                    <a href="../admin/index.php" style="color: #e74c3c;">Admin CP</a>
                <?php endif; ?>
                <a href="../ucp/index.php">Control Panel</a>
                <a href="../auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="../auth/login.php">Login</a>
                <a href="../auth/register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container rankings-container">
        <h1>Server Rankings</h1>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="rankings-grid">
            <div class="ranking-card">
                <h2>Top PvP</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Kills</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topPvp as $index => $char): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($char['char_name']); ?></td>
                                <td><?php echo htmlspecialchars($char['pvpkills']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($topPvp)): ?>
                            <tr><td colspan="3">No data available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="ranking-card">
                <h2>Top PK</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Kills</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topPk as $index => $char): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($char['char_name']); ?></td>
                                <td><?php echo htmlspecialchars($char['pkkills']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($topPk)): ?>
                            <tr><td colspan="3">No data available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="castles-section">
            <h2>Castle Status</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Castle</th>
                        <th>Tax</th>
                        <th>Next Siege</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($castles as $castle): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($castle['name']); ?></td>
                            <td><?php echo htmlspecialchars($castle['taxPercent']); ?>%</td>
                            <td>
                                <?php
                                    if ($castle['siegeDate'] > 0) {
                                        // siegeDate is typically stored in milliseconds in L2J
                                        $timestamp = (int)($castle['siegeDate'] / 1000);
                                        echo date('Y-m-d H:i', $timestamp);
                                    } else {
                                        echo "Not Scheduled";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($castles)): ?>
                        <tr><td colspan="3">No data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
