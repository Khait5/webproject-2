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
    <title>Admin CP - Accounts</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-theme">
    <aside class="admin-sidebar">
        <div class="admin-brand">L2 Admin CP</div>
        <nav class="admin-nav">
            <a href="index.php">Dashboard</a>
            <a href="accounts.php" class="active">Accounts</a>
            <a href="characters.php">Characters</a>
            <a href="clans.php">Clans</a>
            <a href="punishments.php">Punishments</a>
            <a href="../../index.php" class="back-link">← Back to Site</a>
        </nav>
    </aside>

    <main class="admin-content">
        <header class="admin-header">
            <h1>Account Management</h1>
        </header>

        <div class="admin-filter-bar">
            <form action="" method="GET" style="display:flex; width:100%; gap: 1rem;">
                <input type="text" name="search" class="admin-input" placeholder="Search accounts by login..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="admin-btn">Search</button>
            </form>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
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
                            <td><?php echo htmlspecialchars($acc['login']); ?></td>
                            <td><?php echo htmlspecialchars($acc['email'] ?: 'N/A'); ?></td>
                            <td>
                                <?php if ($acc['accessLevel'] > 0): ?>
                                    <span class="badge badge-gm">GM (<?php echo $acc['accessLevel']; ?>)</span>
                                <?php else: ?>
                                    <span class="badge badge-user">User (0)</span>
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
                        <tr><td colspan="4">No accounts found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
