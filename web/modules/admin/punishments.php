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

$punishments = [];
$search = $_GET['search'] ?? '';

$conn = Database::getConnection();
if ($conn) {
    try {
        if ($search) {
            $stmt = $conn->prepare("SELECT `key`, affect, type, expiration, reason, punishedBy FROM punishments WHERE `key` LIKE :search OR punishedBy LIKE :search ORDER BY id DESC LIMIT 50");
            $searchTerm = '%' . $search . '%';
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
        } else {
            $stmt = $conn->query("SELECT `key`, affect, type, expiration, reason, punishedBy FROM punishments ORDER BY id DESC LIMIT 50");
        }
        $punishments = $stmt->fetchAll();
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
    <title>Punishments - Admin CP</title>
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
                        <li><a href="clans.php">Manage Clans</a></li>
                        <li><a href="punishments.php" style="border-color: #e74c3c;">Punishments</a></li>
                        <li><a href="../../index.php" style="color: var(--gold);">Back to Site</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <h2 class="panel-title">Punishments / Bans</h2>

                <div style="margin-bottom: 20px; background: rgba(255,255,255,0.05); padding: 15px; border: 1px solid var(--border-color); border-radius: 4px;">
                    <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                        <input type="text" name="search" style="flex-grow: 1; padding: 8px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 3px;" placeholder="Search by key (account/ip/char) or admin..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Target Key</th>
                                <th>Affect</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Admin</th>
                                <th>Expiration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($punishments as $punish): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($punish['key']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($punish['affect']); ?></td>
                                    <td><span style="color: #e74c3c; font-weight: bold;"><?php echo htmlspecialchars($punish['type']); ?></span></td>
                                    <td><?php echo htmlspecialchars($punish['reason']); ?></td>
                                    <td><?php echo htmlspecialchars($punish['punishedBy']); ?></td>
                                    <td>
                                        <?php
                                            if ($punish['expiration'] > 0) {
                                                echo date('Y-m-d H:i:s', (int)($punish['expiration'] / 1000));
                                            } else {
                                                echo '<span style="color: #e74c3c;">Permanent</span>';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($punishments)): ?>
                                <tr><td colspan="6" style="text-align: center;">No punishments found.</td></tr>
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