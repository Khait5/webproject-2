<?php
require_once __DIR__ . '/core/Session.php';
use Web\Core\Session;
Session::start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ancardia - L2J Mobius Interlude</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <div class="logo">
                <a href="index.php"><img src="assets/img/psd/Ancardia.png" alt="Ancardia Logo"></a>
            </div>
            <nav class="top-nav">
                <a href="index.php">Главная</a>
                <a href="#">Форум</a>
                <a href="modules/auth/register.php">Регистрация</a>
                <a href="#">Пожертвования</a>
                <a href="downloads.php">Файлы</a>
                <a href="modules/rankings/index.php">Статистика</a>
                <a href="#">О проекте</a>
            </nav>
        </header>

        <div class="main-content">
            <!-- Left Sidebar -->
            <aside class="sidebar-left">
                <div class="panel">
                    <div class="panel-header">Навигация</div>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="index.php">Главная</a></li>
                            <li><a href="modules/auth/register.php">Регистрация</a></li>
                            <li><a href="downloads.php">Файлы (Клиент)</a></li>
                            <li><a href="modules/rankings/index.php">Статистика</a></li>
                        </ul>
                    </nav>
                    <div class="panel-footer"></div>
                </div>

                <div class="panel">
                    <div class="panel-header">Мы в соц. сетях</div>
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
                    <div class="panel-header">Новости</div>
                    <div style="padding: 15px;">
                        <h3 style="color:#fff; margin-bottom: 10px;">Добро пожаловать на Ancardia!</h3>
                        <p>Открытие сервера Interlude с классическими рейтами.</p>
                        <p>Присоединяйтесь к нам, чтобы вспомнить старые добрые времена, поучаствовать в эпических осадах и сразиться за право стать легендой!</p>

                        <div style="margin-top:20px; text-align:center;">
                            <?php if (!isset($_SESSION['account'])): ?>
                                <a href="modules/auth/register.php" class="btn" style="width: 200px;">Присоединиться</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar-right">
                <div class="panel">
                    <div class="panel-header">Авторизация</div>
                    <div style="padding: 10px;">
                        <?php if (!isset($_SESSION['account'])): ?>
                            <form action="modules/auth/login.php" method="POST" class="auth-form">
                                <input type="text" name="login" placeholder="Логин" required>
                                <input type="password" name="password" placeholder="Пароль" required>
                                <button type="submit" class="btn" style="margin-top:10px;">Войти</button>
                            </form>
                            <div style="text-align:center; margin-top:10px;">
                                <a href="modules/auth/register.php" style="font-size: 11px;">Регистрация</a> |
                                <a href="#" style="font-size: 11px;">Забыли пароль?</a>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;">
                                <p>Привет, <strong style="color:#fff;"><?php echo htmlspecialchars($_SESSION['account']); ?></strong>!</p>
                                <div class="auth-form" style="margin-top:15px;">
                                    <a href="modules/ucp/index.php" class="btn btn-secondary">Панель управления</a>
                                    <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                                        <a href="modules/admin/index.php" class="btn" style="background:#8b0000;">Админ Панель</a>
                                    <?php endif; ?>
                                    <a href="modules/auth/logout.php" class="btn">Выйти</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer"></div>
                </div>

                <div class="panel">
                    <div class="panel-header">Статус серверов</div>
                    <div class="server-status" style="padding: 15px;">
                        <p><span class="status-indicator"></span><span class="status-online">Online</span></p>
                        <div id="status-online-count" style="font-size:24px; color:#fff; margin:10px 0; text-shadow: 0 0 5px #00ff00;">Loading...</div>
                        <p>Игроков онлайн</p>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </aside>
        </div>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Ancardia Server. Все права защищены.</p>
        </footer>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
