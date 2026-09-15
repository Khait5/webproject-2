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
    <title>Admin CP - Clans</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-theme">
    <aside class="admin-sidebar">
        <div class="admin-brand">L2 Admin CP</div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="accounts.php">Accounts</a>
            <a href="characters.php">Characters</a>
            <a href="clans.php" class="active">Clans</a>
            <a href="punishments.php">Punishments</a>
            <a href="../../index.php" class="back-link">← Back to Site</a>
        </nav>
    </aside>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Clan Management</h1>
        </header>

        <div class="admin-filter-bar">
            <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                <input type="text" name="search" class="admin-input" placeholder="Search clan by name..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="admin-btn">Search</button>
            </form>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
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
                            <td><?php echo htmlspecialchars($clan['clan_name']); ?></td>
                            <td><?php echo htmlspecialchars($clan['clan_level']); ?></td>
                            <td><?php echo htmlspecialchars($clan['reputation_score']); ?></td>
                            <td>
                                <?php if ($clan['hasCastle'] > 0): ?>
                                    <span class="text-warning">Yes (<?php echo $clan['hasCastle']; ?>)</span>
                                <?php else: ?>
                                    No
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($clans)): ?>
                        <tr><td colspan="5">No clans found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
