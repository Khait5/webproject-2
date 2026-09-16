<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';
require_once __DIR__ . '/../../core/Security.php';

use Web\Config\Database;
use Web\Core\Session;
use Web\Core\Security;

Session::start();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $email = trim($_POST['email'] ?? '');

        if (strlen($login) < 4 || strlen($login) > 45 || !preg_match('/^[a-zA-Z0-9]+$/', $login)) {
            $error = "Invalid login format.";
        } elseif (strlen($password) < 6 || strlen($password) > 45) {
            $error = "Password must be between 6 and 45 characters.";
        } else {
            $conn = Database::getConnection();
            if (!$conn) {
                $error = "Database connection failed.";
            } else {
                try {
                    // Check if exists
                    $stmt = $conn->prepare("SELECT login FROM accounts WHERE login = :login LIMIT 1");
                    $stmt->bindParam(':login', $login);
                    $stmt->execute();

                    if ($stmt->fetch()) {
                        $error = "Account already exists.";
                    } else {
                        // Insert new account
                        $hashedPassword = Security::hashPassword($password);

                        $insertStmt = $conn->prepare("INSERT INTO accounts (login, password, email, accessLevel) VALUES (:login, :password, :email, 0)");
                        $insertStmt->bindParam(':login', $login);
                        $insertStmt->bindParam(':password', $hashedPassword);
                        $insertStmt->bindParam(':email', $email);

                        if ($insertStmt->execute()) {
                            $success = "Account created successfully! You can now login.";
                        } else {
                            $error = "Failed to create account.";
                        }
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
    <title>ColombianAge - Register</title>
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
                <h2 class="panel-title">Register Account</h2>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST" id="registerForm" class="auth-form" style="max-width: 400px; margin: 0 auto; gap: 15px;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="login" style="color: var(--gold);">Account Name</label>
                        <input type="text" id="login" name="login" required minlength="4" maxlength="45" pattern="[a-zA-Z0-9]+">
                        <span id="login-status" style="font-size: 12px; margin-top: 2px;"></span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="email" style="color: var(--gold);">Email</label>
                        <input type="email" id="email" name="email">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <label for="password" style="color: var(--gold);">Password</label>
                        <input type="password" id="password" name="password" required minlength="6" maxlength="45">
                    </div>

                    <button type="submit" class="btn" style="margin-top: 10px;">Register</button>
                    <a href="../../index.php" style="text-align: center; display: block; margin-top: 10px; font-size: 12px;">Back to Home</a>
                </form>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ColombianAge Server. Powered by L2J Mobius.</p>
    </footer>

    <script>
        document.getElementById('login').addEventListener('input', function() {
            const login = this.value;
            const statusSpan = document.getElementById('login-status');

            if (login.length >= 4) {
                fetch(`../../api/check_account.php?login=${encodeURIComponent(login)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            statusSpan.textContent = "Available";
                            statusSpan.style.color = "#27ae60";
                        } else {
                            statusSpan.textContent = data.error || "Not available";
                            statusSpan.style.color = "#e74c3c";
                        }
                    })
                    .catch(err => console.error(err));
            } else {
                statusSpan.textContent = "";
            }
        });
    </script>
</body>
</html>