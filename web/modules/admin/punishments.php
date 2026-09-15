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
    <title>Admin CP - Punishments</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-theme">
    <aside class="admin-sidebar">
        <div class="admin-brand">L2 Admin CP</div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="accounts.php">Accounts</a>
            <a href="characters.php">Characters</a>
            <a href="clans.php">Clans</a>
            <a href="punishments.php" class="active">Punishments</a>
            <a href="../../index.php" class="back-link">← Back to Site</a>
        </nav>
    </aside>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Punishments / Bans</h1>
        </header>

        <div class="admin-filter-bar">
            <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                <input type="text" name="search" class="admin-input" placeholder="Search by key (account/ip/char) or admin..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="admin-btn">Search</button>
            </form>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
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
                            <td><?php echo htmlspecialchars($punish['key']); ?></td>
                            <td><?php echo htmlspecialchars($punish['affect']); ?></td>
                            <td><span class="text-danger"><?php echo htmlspecialchars($punish['type']); ?></span></td>
                            <td><?php echo htmlspecialchars($punish['reason']); ?></td>
                            <td><?php echo htmlspecialchars($punish['punishedBy']); ?></td>
                            <td>
                                <?php
                                    if ($punish['expiration'] > 0) {
                                        echo date('Y-m-d H:i:s', (int)($punish['expiration'] / 1000));
                                    } else {
                                        echo 'Permanent';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($punishments)): ?>
                        <tr><td colspan="6">No punishments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
