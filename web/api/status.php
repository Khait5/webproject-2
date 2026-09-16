<?php
require_once __DIR__ . '/../config/database.php';

use Web\Config\Database;

header('Content-Type: application/json');

function checkServerStatus($host, $port, $timeout = 1) {
    $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($socket) {
        fclose($socket);
        return true;
    }
    return false;
}

$status = [
    'login_server' => checkServerStatus('127.0.0.1', 2106),
    'game_server' => checkServerStatus('127.0.0.1', 7777),
    'database' => false,
    'online_players' => 0
];

$conn = Database::getConnection();

if ($conn) {
    $status['database'] = true;
    try {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM characters WHERE online = 1 OR charId IN (SELECT charId FROM character_offline_play) OR charId IN (SELECT charId FROM character_offline_trade)");
        $row = $stmt->fetch();
        if ($row) {
            $status['online_players'] = (int)$row['count'];
        }
    } catch (\PDOException $e) {
        // Log error in a real app, silently fail for API
    }
}

echo json_encode($status);
