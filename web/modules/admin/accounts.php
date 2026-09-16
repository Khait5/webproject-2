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

$accounts = [];
$search = $_GET['search'] ?? '';

$conn = Database::getConnection();
if ($conn) {
    try {
        if ($search) {
            $stmt = $conn->prepare("SELECT login, email, accessLevel, lastactive FROM accounts WHERE login LIKE :search ORDER BY login ASC LIMIT 50");
            $searchTerm = '%' . $search . '%';
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
        } else {
            $stmt = $conn->query("SELECT login, email, accessLevel, lastactive FROM accounts ORDER BY login ASC LIMIT 50");
        }
        $accounts = $stmt->fetchAll();
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
    <title>Account Management - Admin CP</title>
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
                        <li><a href="accounts.php" style="border-color: #e74c3c;">Manage Accounts</a></li>
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
                <h2 class="panel-title">Account Management</h2>

                <div style="margin-bottom: 20px; background: rgba(255,255,255,0.05); padding: 15px; border: 1px solid var(--border-color); border-radius: 4px;">
                    <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                        <input type="text" name="search" style="flex-grow: 1; padding: 8px; background: #1a1a1a; border: 1px solid var(--border-color); color: #fff; border-radius: 3px;" placeholder="Search accounts by login..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Login</th>
                                <th>Email</th>
                                <th>Access Level</th>
                                <th>Last Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accounts as $acc): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($acc['login']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($acc['email'] ?: 'N/A'); ?></td>
                                    <td>
                                        <?php if ($acc['accessLevel'] > 0): ?>
                                            <span style="background: rgba(231, 76, 60, 0.2); color: #e74c3c; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: bold;">GM (<?php echo $acc['accessLevel']; ?>)</span>
                                        <?php else: ?>
                                            <span style="background: rgba(255, 255, 255, 0.1); color: #ccc; padding: 2px 8px; border-radius: 10px; font-size: 12px;">User (0)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            if ($acc['lastactive'] > 0) {
                                                echo date('Y-m-d H:i:s', (int)($acc['lastactive'] / 1000));
                                            } else {
                                                echo 'Never';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($accounts)): ?>
                                <tr><td colspan="4" style="text-align: center;">No accounts found.</td></tr>
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