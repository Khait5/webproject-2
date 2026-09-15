<?php
require_once __DIR__ . '/../config/database.php';

use Web\Config\Database;

header('Content-Type: application/json');

$response = ['available' => false, 'error' => null];

if (isset($_GET['login'])) {
    $login = trim($_GET['login']);

    if (strlen($login) < 4 || strlen($login) > 45) {
        $response['error'] = 'Login must be between 4 and 45 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $login)) {
        $response['error'] = 'Login must be alphanumeric.';
    } else {
        $conn = Database::getConnection();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT login FROM accounts WHERE login = :login LIMIT 1");
                $stmt->bindParam(':login', $login);
                $stmt->execute();

                if ($stmt->fetch()) {
                    $response['available'] = false;
                } else {
                    $response['available'] = true;
                }
            } catch (\PDOException $e) {
                $response['error'] = 'Database error.';
            }
        } else {
            $response['error'] = 'Database connection failed.';
        }
    }
} else {
    $response['error'] = 'No login provided.';
}

echo json_encode($response);
