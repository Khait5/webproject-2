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
<body class="dark-fantasy-theme">
    <div class="container">
        <h1>Login</h1>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div class="form-group">
                <label for="login">Account Name</label>
                <input type="text" id="login" name="login" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
            <a href="../../index.php" class="btn-link">Back to Home</a>
        </form>
    </div>
</body>
</html>
