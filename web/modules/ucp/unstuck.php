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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
        header("Location: index.php?err=" . urlencode("Invalid CSRF token."));
        exit;
    }

    $charId = (int)($_POST['charId'] ?? 0);
    $account = $_SESSION['account'];

    if ($charId > 0) {
        $conn = Database::getConnection();
        if ($conn) {
            try {
                // Verify ownership and offline status
                $stmt = $conn->prepare("SELECT online FROM characters WHERE charId = :charId AND account_name = :account LIMIT 1");
                $stmt->bindParam(':charId', $charId);
                $stmt->bindParam(':account', $account);
                $stmt->execute();

                $char = $stmt->fetch();
                if ($char) {
                    if ($char['online'] == 0) {
                        // Giran coordinates (Interlude)
                        $x = 83400;
                        $y = 147943;
                        $z = -3404;

                        $updateStmt = $conn->prepare("UPDATE characters SET x = :x, y = :y, z = :z WHERE charId = :charId");
                        $updateStmt->bindParam(':x', $x);
                        $updateStmt->bindParam(':y', $y);
                        $updateStmt->bindParam(':z', $z);
                        $updateStmt->bindParam(':charId', $charId);

                        if ($updateStmt->execute()) {
                            header("Location: index.php?msg=" . urlencode("Character successfully moved to Giran."));
                            exit;
                        } else {
                            header("Location: index.php?err=" . urlencode("Failed to update character location."));
                            exit;
                        }
                    } else {
                        header("Location: index.php?err=" . urlencode("Character must be offline to use Unstuck."));
                        exit;
                    }
                } else {
                    header("Location: index.php?err=" . urlencode("Character not found or doesn't belong to you."));
                    exit;
                }
            } catch (\PDOException $e) {
                header("Location: index.php?err=" . urlencode("Database error occurred."));
                exit;
            }
        } else {
            header("Location: index.php?err=" . urlencode("Database connection failed."));
            exit;
        }
    } else {
        header("Location: index.php?err=" . urlencode("Invalid character ID."));
        exit;
    }
}

header("Location: index.php");
exit;
