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
    <title>User Control Panel - ColombianAge</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- Left Sidebar: UCP Menu -->
        <aside class="sidebar-left">
            <div class="panel">
                <h2 class="panel-title">UCP Menu</h2>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php" style="border-color: var(--gold);">Characters</a></li>
                        <li><a href="password.php">Change Password</a></li>
                        <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                            <li><a href="../admin/index.php" style="color: #e74c3c;">Admin CP</a></li>
                        <?php endif; ?>
                        <li><a href="../../index.php">Back to Site</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <h2 class="panel-title">Welcome, <?php echo htmlspecialchars($account); ?></h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['err'])): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($_GET['err']); ?></div>
                <?php endif; ?>

                <h3 style="color: var(--gold); margin-top: 20px;">Your Characters</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Level</th>
                                <th>PvP / PK</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($characters)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">No characters found on this account.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($characters as $char): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($char['char_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($char['level']); ?></td>
                                        <td><span style="color:#27ae60"><?php echo htmlspecialchars($char['pvpkills']); ?></span> / <span style="color:#e74c3c"><?php echo htmlspecialchars($char['pkkills']); ?></span></td>
                                        <td>
                                            <?php if ($char['online'] == 1): ?>
                                                <span style="color:#27ae60; font-weight:bold;">Online</span>
                                            <?php else: ?>
                                                <span style="color:#888;">Offline</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($char['online'] == 0): ?>
                                                <form action="unstuck.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(Session::generateCsrfToken()); ?>">
                                                    <input type="hidden" name="charId" value="<?php echo htmlspecialchars($char['charId']); ?>">
                                                    <button type="submit" class="btn" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Are you sure you want to unstuck this character to Giran?');">Unstuck</button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;" disabled title="Character must be offline">Unstuck</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Powered by L2J Mobius.</p>
    </footer>
</body>
</html>