<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

use Web\Config\Database;

$status = [
    'online' => false,
    'online_players' => 0
];

$conn = Database::getConnection();
if ($conn) {
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM characters WHERE online = 1");
        $count = $stmt->fetchColumn();

        // Count offline shops/bots if they exist (Living World requirements)
        try {
            $offlinePlayStmt = $conn->query("SELECT COUNT(*) FROM character_offline_play");
            $count += $offlinePlayStmt->fetchColumn();
        } catch (\Exception $e) {}

        try {
            $offlineTradeStmt = $conn->query("SELECT COUNT(*) FROM character_offline_trade");
            $count += $offlineTradeStmt->fetchColumn();
        } catch (\Exception $e) {}

        $status['online'] = true;
        $status['online_players'] = $count;
    } catch (\PDOException $e) {
        $status['online'] = false;
    }
}

echo json_encode($status);
