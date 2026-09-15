<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';
require_once __DIR__ . '/../../core/Security.php';

use Web\Config\Database;
use Web\Core\Session;
use Web\Core\Security;

Session::start();

if (!isset($_SESSION['account'])) {
    header("Location: ../auth/login.php");
    exit;
}

$account = $_SESSION['account'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = "All fields are required.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "New passwords do not match.";
        } elseif (strlen($newPassword) < 6 || strlen($newPassword) > 45) {
            $error = "New password must be between 6 and 45 characters.";
        } else {
            $conn = Database::getConnection();
            if ($conn) {
                try {
                    // Verify current password
                    $stmt = $conn->prepare("SELECT password FROM accounts WHERE login = :login LIMIT 1");
                    $stmt->bindParam(':login', $account);
                    $stmt->execute();
                    $user = $stmt->fetch();

                    if ($user && Security::verifyPassword($currentPassword, $user['password'])) {
                        // Update password
                        $hashedNewPassword = Security::hashPassword($newPassword);
                        $updateStmt = $conn->prepare("UPDATE accounts SET password = :password WHERE login = :login");
                        $updateStmt->bindParam(':password', $hashedNewPassword);
                        $updateStmt->bindParam(':login', $account);

                        if ($updateStmt->execute()) {
                            $success = "Password successfully updated.";
                        } else {
                            $error = "Failed to update password.";
                        }
                    } else {
                        $error = "Incorrect current password.";
                    }
                } catch (\PDOException $e) {
                    $error = "Database error occurred.";
                }
            } else {
                $error = "Database connection failed.";
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
    <title>ColombianAge - Change Password</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="dark-fantasy-theme">
    <nav class="navbar">
        <div class="nav-brand">ColombianAge</div>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Change Password</h1>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="password.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required minlength="6" maxlength="45">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6" maxlength="45">
            </div>

            <button type="submit" class="btn">Update Password</button>
            <a href="index.php" class="btn-link">Cancel</a>
        </form>
    </div>
</body>
</html>
