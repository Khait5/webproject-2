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
    <title>Rankings - ColombianAge</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- Left Sidebar: Navigation -->
        <aside class="sidebar-left">
            <div class="panel">
                <h2 class="panel-title">Main Menu</h2>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="../../index.php">Home</a></li>
                        <li><a href="../auth/register.php">Register</a></li>
                        <li><a href="../../downloads.php">Downloads</a></li>
                        <li><a href="index.php" style="border-color: var(--gold);">Rankings</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <h2 class="panel-title">Server Rankings</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
                    <div style="flex: 1; min-width: 300px;">
                        <h3 style="color: var(--gold); border-bottom: 1px solid var(--border-color); padding-bottom: 5px;">Top PvP</h3>
                        <div class="table-responsive">
                            <table>
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
                                            <td style="color: #27ae60; font-weight: bold;"><?php echo htmlspecialchars($char['pvpkills']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($topPvp)): ?>
                                        <tr><td colspan="3">No data available.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="flex: 1; min-width: 300px;">
                        <h3 style="color: var(--gold); border-bottom: 1px solid var(--border-color); padding-bottom: 5px;">Top PK</h3>
                        <div class="table-responsive">
                            <table>
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
                                            <td style="color: #e74c3c; font-weight: bold;"><?php echo htmlspecialchars($char['pkkills']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($topPk)): ?>
                                        <tr><td colspan="3">No data available.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 style="color: var(--gold); border-bottom: 1px solid var(--border-color); padding-bottom: 5px;">Castle Status</h3>
                    <div class="table-responsive">
                        <table>
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
                                        <td><strong><?php echo htmlspecialchars($castle['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($castle['taxPercent']); ?>%</td>
                                        <td>
                                            <?php
                                                if ($castle['siegeDate'] > 0) {
                                                    $timestamp = (int)($castle['siegeDate'] / 1000);
                                                    echo date('Y-m-d H:i', $timestamp);
                                                } else {
                                                    echo "<span style='color:#888'>Not Scheduled</span>";
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
            </div>
        </main>

        <!-- Right Sidebar: Auth & Server Status -->
        <aside class="sidebar-right">
            <div class="panel">
                <h2 class="panel-title">Account</h2>
                <?php if (!isset($_SESSION['account'])): ?>
                    <form action="../auth/login.php" method="POST" class="auth-form">
                        <input type="text" name="login" placeholder="Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" class="btn">Login</button>
                    </form>
                    <div style="text-align:center; margin-top:10px;">
                        <a href="../auth/register.php" style="font-size: 12px;">Create Account</a>
                    </div>
                <?php else: ?>
                    <div style="text-align:center;">
                        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['account']); ?></strong>!</p>
                        <div class="auth-form" style="margin-top:15px;">
                            <a href="../ucp/index.php" class="btn btn-secondary">User Panel</a>
                            <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                                <a href="../admin/index.php" class="btn" style="background:#e74c3c;">Admin CP</a>
                            <?php endif; ?>
                            <a href="../auth/logout.php" class="btn">Logout</a>
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