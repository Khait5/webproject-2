<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

if (!isset($_SESSION['account'])) {
    header("Location: ../auth/login.php");
    exit;
}

$account = $_SESSION['account'];
$characters = [];
$error = '';

$conn = Database::getConnection();
if ($conn) {
    try {
        $stmt = $conn->prepare("SELECT charId, char_name, level, pvpkills, pkkills, online, clanid FROM characters WHERE account_name = :account");
        $stmt->bindParam(':account', $account);
        $stmt->execute();
        $characters = $stmt->fetchAll();
    } catch (\PDOException $e) {
        $error = "Failed to load characters.";
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
    <title>ColombianAge - User Control Panel</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="dark-fantasy-theme">
    <nav class="navbar">
        <div class="nav-brand">ColombianAge</div>
        <div class="nav-links">
            <a href="../../index.php">Home</a>
            <a href="password.php">Change Password</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </nav>

    <div class="container ucp-container">
        <h1>Welcome, <?php echo htmlspecialchars($account); ?></h1>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['err'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['err']); ?></div>
        <?php endif; ?>

        <h2>Your Characters</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Level</th>
                        <th>PvP</th>
                        <th>PK</th>
                        <th>Status</th>
                        <th>Clan ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($characters)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No characters found on this account.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($characters as $char): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($char['char_name']); ?></td>
                                <td><?php echo htmlspecialchars($char['level']); ?></td>
                                <td><?php echo htmlspecialchars($char['pvpkills']); ?></td>
                                <td><?php echo htmlspecialchars($char['pkkills']); ?></td>
                                <td>
                                    <?php if ($char['online'] == 1): ?>
                                        <span class="status-online">Online</span>
                                    <?php else: ?>
                                        <span class="status-offline">Offline</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($char['clanid'] ?: 'None'); ?></td>
                                <td>
                                    <?php if ($char['online'] == 0): ?>
                                        <form action="unstuck.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(Session::generateCsrfToken()); ?>">
                                            <input type="hidden" name="charId" value="<?php echo htmlspecialchars($char['charId']); ?>">
                                            <button type="submit" class="btn btn-small" onclick="return confirm('Are you sure you want to unstuck this character to Giran?');">Unstuck</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-small" disabled title="Character must be offline">Unstuck</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
