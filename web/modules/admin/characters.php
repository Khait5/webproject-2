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

$characters = [];
$search = $_GET['search'] ?? '';

$conn = Database::getConnection();
if ($conn) {
    try {
        if ($search) {
            $stmt = $conn->prepare("SELECT account_name, char_name, level, online, clanid, accesslevel FROM characters WHERE char_name LIKE :search OR account_name LIKE :search ORDER BY level DESC LIMIT 50");
            $searchTerm = '%' . $search . '%';
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
        } else {
            $stmt = $conn->query("SELECT account_name, char_name, level, online, clanid, accesslevel FROM characters ORDER BY level DESC LIMIT 50");
        }
        $characters = $stmt->fetchAll();
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
    <title>Character Management - Admin CP</title>
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
                        <li><a href="characters.php" style="border-color: #e74c3c;">Manage Characters</a></li>
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
                <h2 class="panel-title">Character Management</h2>

                <div style="margin-bottom: 20px; background: rgba(255,255,255,0.05); padding: 15px; border: 1px solid var(--border-color); border-radius: 4px;">
                    <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                        <input type="text" name="search" style="flex-grow: 1; padding: 8px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 3px;" placeholder="Search by char name or account..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Char Name</th>
                                <th>Account</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Clan ID</th>
                                <th>Access</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($characters as $char): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($char['char_name']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($char['account_name']); ?></td>
                                    <td><?php echo htmlspecialchars($char['level']); ?></td>
                                    <td>
                                        <?php if ($char['online'] == 1): ?>
                                            <span style="color: #27ae60; font-weight: bold;">Online</span>
                                        <?php else: ?>
                                            <span style="color: #888;">Offline</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($char['clanid'] ?: 'None'); ?></td>
                                    <td>
                                        <?php if ($char['accesslevel'] > 0): ?>
                                            <span style="background: rgba(231, 76, 60, 0.2); color: #e74c3c; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: bold;">GM</span>
                                        <?php else: ?>
                                            <span style="background: rgba(255, 255, 255, 0.1); color: #ccc; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Normal</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($characters)): ?>
                                <tr><td colspan="6" style="text-align: center;">No characters found.</td></tr>
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