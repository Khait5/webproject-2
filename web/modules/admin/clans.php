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

$clans = [];
$search = $_GET['search'] ?? '';

$conn = Database::getConnection();
if ($conn) {
    try {
        if ($search) {
            $stmt = $conn->prepare("SELECT clan_id, clan_name, clan_level, reputation_score, hasCastle FROM clan_data WHERE clan_name LIKE :search ORDER BY clan_level DESC LIMIT 50");
            $searchTerm = '%' . $search . '%';
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
        } else {
            $stmt = $conn->query("SELECT clan_id, clan_name, clan_level, reputation_score, hasCastle FROM clan_data ORDER BY clan_level DESC LIMIT 50");
        }
        $clans = $stmt->fetchAll();
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
    <title>Clan Management - Admin CP</title>
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
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="accounts.php">Manage Accounts</a></li>
                        <li><a href="characters.php">Manage Characters</a></li>
                        <li><a href="clans.php" style="border-color: #e74c3c;">Manage Clans</a></li>
                        <li><a href="punishments.php">Punishments</a></li>
                        <li><a href="../../index.php" style="color: var(--gold);">Back to Site</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <h2 class="panel-title">Clan Management</h2>

                <div style="margin-bottom: 20px; background: rgba(255,255,255,0.05); padding: 15px; border: 1px solid var(--border-color); border-radius: 4px;">
                    <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                        <input type="text" name="search" style="flex-grow: 1; padding: 8px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 3px;" placeholder="Search clan by name..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Clan ID</th>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Reputation</th>
                                <th>Has Castle (ID)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clans as $clan): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($clan['clan_id']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($clan['clan_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($clan['clan_level']); ?></td>
                                    <td><?php echo htmlspecialchars($clan['reputation_score']); ?></td>
                                    <td>
                                        <?php if ($clan['hasCastle'] > 0): ?>
                                            <span style="color: var(--gold); font-weight: bold;">Yes (<?php echo $clan['hasCastle']; ?>)</span>
                                        <?php else: ?>
                                            <span style="color: #888;">No</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($clans)): ?>
                                <tr><td colspan="5" style="text-align: center;">No clans found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Admin Control Panel.</p>
    </footer>
</body>
</html>