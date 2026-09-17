<?php
require_once __DIR__ . '/core/Session.php';
use Web\Core\Session;
Session::start();
$csrf_token = Session::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files - Ancardia</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                <a href="index.php"><img src="assets/img/psd/Ancardia.png" alt="Ancardia Logo"></a>
            </div>
            <nav class="top-nav">
                <a href="index.php">Home</a>
                <a href="#">Forum</a>
                <?php if (!isset($_SESSION['account'])): ?><a href="modules/auth/register.php">Register</a><?php endif; ?>
                <a href="#">Donate</a>
                <a href="downloads.php">Files</a>
                <a href="modules/rankings/index.php">Rankings</a>
                <a href="#">About</a>
            </nav>
        </header>

        <div class="main-content">
            <!-- Left Sidebar -->
            <aside class="sidebar-left">
                <div class="panel">
                    <div class="panel-header">Navigation</div>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><?php if (!isset($_SESSION['account'])): ?><a href="modules/auth/register.php">Register</a><?php endif; ?></li>
                            <li><a href="downloads.php">Files (Client)</a></li>
                            <li><a href="modules/rankings/index.php">Rankings</a></li>
                        </ul>
                    </nav>
                    <div class="panel-footer"></div>
                </div>

                <div class="panel">
                    <div class="panel-header">Social Media</div>
                    <div style="text-align: center; padding: 10px;">
                        <a href="#"><img src="assets/img/psd/Vkontakte.png" alt="VK"></a>
                        <a href="#"><img src="assets/img/psd/Facebook.png" alt="Facebook"></a>
                        <a href="#"><img src="assets/img/psd/Youtube.png" alt="YouTube"></a>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </aside>

            <!-- Center Content -->
            <main class="content-center">
                <div class="panel">
                    <div class="panel-header">Game Files</div>
                    <div style="padding: 20px; text-align: center;">
                        <h3 style="color:#fff; margin-bottom: 20px;">Download Client Lineage 2 Interlude</h3>
                        <p style="margin-bottom: 15px;">To play on the server you need to download and install the clean Interlude client, and then install our patch.</p>

                        <div style="margin-bottom: 30px;">
                            <strong style="color: #4da6ff;">Client Size:</strong> ~3.2 GB<br>
                            <a href="#" class="btn" style="width: 250px; margin-top: 15px;">Download Client (Torrent)</a>
                        </div>

                        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                            <h3 style="color:#fff; margin-bottom: 15px;">Download Patch</h3>
                            <p style="margin-bottom: 15px;">If you already have the Interlude client, just download and extract our patch into the game folder replacing files.</p>
                            <strong style="color: #4da6ff;">Patch Size:</strong> ~45 MB<br>
                            <a href="#" class="btn" style="width: 250px; margin-top: 15px;">Download Patch (Mega)</a>
                        </div>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar-right">
                <div class="panel">
                    <div class="panel-header">Authorization</div>
                    <div style="padding: 10px;">
                        <?php if (!isset($_SESSION['account'])): ?>
                            <form action="modules/auth/login.php" method="POST" class="auth-form">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                <input type="text" name="login" placeholder="Login" autocomplete="username" required>
                                <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                                <button type="submit" class="btn" style="margin-top:10px;">Login</button>
                            </form>
                            <div style="text-align:center; margin-top:10px;">
                                <a href="modules/auth/register.php" style="font-size: 11px;">Register</a> |
                                <a href="#" style="font-size: 11px;">Forgot Password?</a>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;">
                                <p>Hello, <strong style="color:#fff;"><?php echo htmlspecialchars($_SESSION['account']); ?></strong>!</p>
                                <div class="auth-form" style="margin-top:15px;">
                                    <a href="modules/ucp/index.php" class="btn btn-secondary">User Control Panel</a>
                                    <a href="modules/auth/logout.php" class="btn">Logout</a>
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

    <script src="assets/js/main.js"></script>
</body>
</html>
