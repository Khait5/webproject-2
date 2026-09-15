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
    <title>Admin CP - Characters</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-theme">
    <aside class="admin-sidebar">
        <div class="admin-brand">L2 Admin CP</div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="accounts.php">Accounts</a>
            <a href="characters.php" class="active">Characters</a>
            <a href="clans.php">Clans</a>
            <a href="punishments.php">Punishments</a>
            <a href="../../index.php" class="back-link">← Back to Site</a>
        </nav>
    </aside>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Character Management</h1>
        </header>

        <div class="admin-filter-bar">
            <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                <input type="text" name="search" class="admin-input" placeholder="Search by char name or account..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="admin-btn">Search</button>
            </form>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
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
                            <td><?php echo htmlspecialchars($char['char_name']); ?></td>
                            <td><?php echo htmlspecialchars($char['account_name']); ?></td>
                            <td><?php echo htmlspecialchars($char['level']); ?></td>
                            <td>
                                <?php if ($char['online'] == 1): ?>
                                    <span class="text-success">Online</span>
                                <?php else: ?>
                                    <span class="text-danger">Offline</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($char['clanid'] ?: 'None'); ?></td>
                            <td>
                                <?php if ($char['accesslevel'] > 0): ?>
                                    <span class="badge badge-gm">GM</span>
                                <?php else: ?>
                                    <span class="badge badge-user">Normal</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($characters)): ?>
                        <tr><td colspan="6">No characters found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
