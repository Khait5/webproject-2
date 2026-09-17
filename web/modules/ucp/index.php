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
    <title>User Control Panel - Ancardia</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <div id="google_translate_element" style="position: absolute; right: 20px; top: 10px;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'es,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            <div class="logo">
                <a href="../../index.php"><img src="../../assets/img/psd/Ancardia.png" alt="Ancardia Logo"></a>
            </div>
            <nav class="top-nav">
                <a href="../../index.php">Home</a>
                <a href="../../modules/forum/index.php">Forum</a>
                <?php if (!isset($_SESSION['account'])): ?><a href="../../modules/auth/register.php">Register</a><?php endif; ?>
                <a href="#">Donate</a>
                <a href="../../downloads.php">Files</a>
                <a href="../../modules/rankings/index.php">Rankings</a>
                <a href="#">About</a>
            </nav>
        </header>

        <div class="main-content">
            <!-- Left Sidebar -->
            <aside class="sidebar-left">
                <div class="panel">
                    <div class="panel-header">UCP Navigation</div>
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
                    <div class="panel-footer"></div>
                </div>
                <div class="panel">
                    <div class="panel-header">Navigation</div>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="../../index.php">Home</a></li>
                            <li><?php if (!isset($_SESSION['account'])): ?><a href="../../modules/auth/register.php">Register</a><?php endif; ?></li>
                            <li><a href="../../downloads.php">Files (Client)</a></li>
                            <li><a href="../../modules/rankings/index.php">Rankings</a></li>
                        </ul>
                    </nav>
                    <div class="panel-footer"></div>
                </div>

                <div class="panel">
                    <div class="panel-header">Social Media</div>
                    <div style="text-align: center; padding: 10px;">
                        <a href="#"><img src="../../assets/img/psd/Vkontakte.png" alt="VK"></a>
                        <a href="#"><img src="../../assets/img/psd/Facebook.png" alt="Facebook"></a>
                        <a href="#"><img src="../../assets/img/psd/Youtube.png" alt="YouTube"></a>
                    </div>
                    <div class="panel-footer"></div>
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

            <!-- Right Sidebar -->
            <aside class="sidebar-right">
                <div class="panel">
                    <div class="panel-header">Authorization</div>
                    <div style="padding: 10px;">
                        <?php if (!isset($_SESSION['account'])): ?>
                            <form action="../../modules/auth/login.php" method="POST" class="auth-form">
                                <input type="text" name="login" placeholder="Login" autocomplete="username" required>
                                <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                                <button type="submit" class="btn" style="margin-top:10px;">Login</button>
                            </form>
                            <div style="text-align:center; margin-top:10px;">
                                <a href="../../modules/auth/register.php" style="font-size: 11px;">Register</a> |
                                <a href="#" style="font-size: 11px;">Forgot Password?</a>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;">
                                <p>Hello, <strong style="color:#fff;"><?php echo htmlspecialchars($_SESSION['account']); ?></strong>!</p>
                                <div class="auth-form" style="margin-top:15px;">
                                    <a href="../../modules/ucp/index.php" class="btn btn-secondary">User Control Panel</a>
                                    <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                                        <a href="../../modules/admin/index.php" class="btn" style="background:#8b0000;">Admin Panel</a>
                                    <?php endif; ?>
                                    <a href="../../modules/auth/logout.php" class="btn">Logout</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer"></div>
                </div>

                <div class="panel">
                    <div class="panel-header">Server Status</div>
                    <div class="server-status" style="padding: 15px;">
                        <p><span class="status-indicator"></span><span class="status-online">Online</span></p>
                        <div id="status-online-count" style="font-size:24px; color:#fff; margin:10px 0; text-shadow: 0 0 5px #00ff00;">Loading...</div>
                        <p>Players Online</p>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </aside>
        </div>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Ancardia Server. All rights reserved.</p>
        </footer>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
