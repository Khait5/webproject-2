<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

if (!isset($_SESSION['account']) || !isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] <= 0) {
    header("Location: ../../index.php");
    die();
}

$stats = [
    'accounts' => 0,
    'characters' => 0,
    'online' => 0,
    'clans' => 0
];

$conn = Database::getConnection();
if ($conn) {
    try {
        $stats['accounts'] = $conn->query("SELECT COUNT(*) FROM accounts")->fetchColumn();
        $stats['characters'] = $conn->query("SELECT COUNT(*) FROM characters")->fetchColumn();
        $stats['online'] = $conn->query("SELECT COUNT(*) FROM characters WHERE online = 1")->fetchColumn();
        $stats['clans'] = $conn->query("SELECT COUNT(*) FROM clan_data")->fetchColumn();
    } catch (\PDOException $e) {
        // Handle silently
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Ancardia</title>
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
                <a href="../../index.php">ГЛАВНАЯ</a>
                <a href="#">ФОРУМ</a>
                <a href="../auth/register.php">REGISTER</a>
                <a href="#">ПОЖЕРТВОВАНИЯ</a>
                <a href="../../downloads.php">FILES</a>
                <a href="../rankings/index.php">RANKINGS</a>
                <a href="#">ABOUT</a>
            </nav>
        </header>

        <div class="main-content">
            <!-- Left Sidebar -->
            <aside class="sidebar-left">
                <div class="panel">
                    <div class="panel-header">ADMIN MENU</div>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="index.php" style="color: var(--text-blue);">Dashboard</a></li>
                            <li><a href="../../index.php">Site Home</a></li>
                        </ul>
                    </nav>
                    <div class="panel-footer"></div>
                </div>
            </aside>

            <!-- Center Content -->
            <main class="content-center">
                <div class="panel center-panel">
                    <div class="panel-header">DASHBOARD OVERVIEW</div>

                    <div style="padding: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 20px; color: #fff;">
                            <div>Logged in as: <strong style="color: var(--text-blue);"><?php echo htmlspecialchars($_SESSION['account']); ?></strong></div>
                        </div>

                        <div style="display: flex; gap: 15px; justify-content: space-between; flex-wrap: wrap;">
                            <div style="flex: 1; background: rgba(0,0,0,0.5); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid rgba(255,255,255,0.1); box-shadow: inset 0 0 10px rgba(0,0,0,0.8);">
                                <h3 style="margin-top: 0; color: #aaa; font-size: 14px;">Total Accounts</h3>
                                <div style="font-size: 28px; font-weight: bold; color: var(--text-blue);"><?php echo number_format($stats['accounts']); ?></div>
                            </div>
                            <div style="flex: 1; background: rgba(0,0,0,0.5); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid rgba(255,255,255,0.1); box-shadow: inset 0 0 10px rgba(0,0,0,0.8);">
                                <h3 style="margin-top: 0; color: #aaa; font-size: 14px;">Total Characters</h3>
                                <div style="font-size: 28px; font-weight: bold; color: var(--text-blue);"><?php echo number_format($stats['characters']); ?></div>
                            </div>
                            <div style="flex: 1; background: rgba(0,0,0,0.5); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid rgba(0,255,0,0.3); box-shadow: inset 0 0 10px rgba(0,0,0,0.8);">
                                <h3 style="margin-top: 0; color: #aaa; font-size: 14px;">Players Online</h3>
                                <div style="font-size: 28px; font-weight: bold; color: var(--text-green); text-shadow: 0 0 5px #00ff00;"><?php echo number_format($stats['online']); ?></div>
                            </div>
                            <div style="flex: 1; background: rgba(0,0,0,0.5); padding: 15px; border-radius: 4px; text-align: center; border: 1px solid rgba(255,255,255,0.1); box-shadow: inset 0 0 10px rgba(0,0,0,0.8);">
                                <h3 style="margin-top: 0; color: #aaa; font-size: 14px;">Total Clans</h3>
                                <div style="font-size: 28px; font-weight: bold; color: var(--text-blue);"><?php echo number_format($stats['clans']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar-right">
                <div class="panel">
                    <div class="panel-header">SERVER STATUS</div>
                    <div class="server-status" style="padding: 20px 15px; text-align: center;">
                        <p style="color: var(--text-green); font-weight: bold; margin-bottom: 10px;">Online</p>
                        <div id="status-online-count" style="font-size: 28px; color: #fff; font-weight: bold; margin-bottom: 10px; text-shadow: 0 0 5px rgba(255,255,255,0.5);">0</div>
                        <p style="color: var(--text-gray); font-size: 12px;">Players Online</p>
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
