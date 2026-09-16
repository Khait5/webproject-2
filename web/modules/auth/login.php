<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';
require_once __DIR__ . '/../../core/Security.php';

use Web\Config\Database;
use Web\Core\Session;
use Web\Core\Security;

Session::start();

if (isset($_SESSION['account'])) {
    header("Location: ../ucp/index.php");
    exit;
}

$error = '';

// Initialize failed attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        Security::bruteForceDelay($_SESSION['login_attempts']);

        if (empty($login) || empty($password)) {
            $error = "Please enter both login and password.";
        } else {
            $conn = Database::getConnection();
            if (!$conn) {
                $error = "Database connection failed.";
            } else {
                try {
                    $stmt = $conn->prepare("SELECT login, password, accessLevel FROM accounts WHERE login = :login LIMIT 1");
                    $stmt->bindParam(':login', $login);
                    $stmt->execute();
                    $account = $stmt->fetch();

                    if ($account && Security::verifyPassword($password, $account['password'])) {
                        // Success
                        Session::regenerate();
                        $_SESSION['account'] = $account['login'];
                        $_SESSION['accessLevel'] = $account['accessLevel'];
                        $_SESSION['login_attempts'] = 0; // reset attempts

                        header("Location: ../ucp/index.php");
                        exit;
                    } else {
                        $_SESSION['login_attempts']++;
                        $error = "Invalid login or password.";
                    }
                } catch (\PDOException $e) {
                    $error = "Database error occurred.";
                }
            }
        }
    }
}

$csrf_token = Session::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ColombianAge - Login</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- Left Sidebar: Navigation -->
        <aside class="sidebar-left">
            <div class="panel">
                <h2 class="panel-title">Main Menu</h2>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="../../index.php">Home</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="../../downloads.php">Downloads</a></li>
                        <li><a href="../rankings/index.php">Rankings</a></li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Center Content -->
        <main class="content-center">
            <div class="panel">
                <h2 class="panel-title">Login</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST" class="auth-form" style="max-width: 400px; margin: 0 auto; gap: 15px;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="login" style="color: var(--gold);">Account Name</label>
                        <input type="text" id="login" name="login" required>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="password" style="color: var(--gold);">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn" style="margin-top: 10px;">Login</button>
                    <a href="../../index.php" style="text-align: center; display: block; margin-top: 10px; font-size: 12px;">Back to Home</a>
                </form>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Powered by L2J Mobius.</p>
    </footer>
</body>
</html>