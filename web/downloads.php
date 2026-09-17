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
    <title>Файлы - Ancardia</title>
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
                    <div class="panel-header">Файлы для игры</div>
                    <div style="padding: 20px; text-align: center;">
                        <h3 style="color:#fff; margin-bottom: 20px;">Скачать клиент Lineage 2 Interlude</h3>
                        <p style="margin-bottom: 15px;">Для игры на сервере вам необходимо скачать и установить чистый клиент версии Interlude, а затем установить наш патч.</p>

                        <div style="margin-bottom: 30px;">
                            <strong style="color: #4da6ff;">Размер клиента:</strong> ~3.2 GB<br>
                            <a href="#" class="btn" style="width: 250px; margin-top: 15px;">Скачать Клиент (Торрент)</a>
                        </div>

                        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                            <h3 style="color:#fff; margin-bottom: 15px;">Скачать патч</h3>
                            <p style="margin-bottom: 15px;">Если у вас уже есть клиент Interlude, просто скачайте и распакуйте наш патч в папку с игрой с заменой файлов.</p>
                            <strong style="color: #4da6ff;">Размер патча:</strong> ~45 MB<br>
                            <a href="#" class="btn" style="width: 250px; margin-top: 15px;">Скачать Патч (Mega)</a>
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
