<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();
$csrf_token = Session::generateCsrfToken();

if (!isset($_SESSION['account'])) {
    header("Location: ../auth/login.php");
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    die();
}

$topicId = isset($_POST['topic_id']) ? (int)$_POST['topic_id'] : 0;
$content = trim($_POST['content'] ?? '');

if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
    header("Location: topic.php?id=" . $topicId . "&err=" . urlencode("Invalid CSRF token."));
    die();
}

if (empty($content) || $topicId <= 0) {
    header("Location: topic.php?id=" . $topicId . "&err=" . urlencode("Post content cannot be empty."));
    die();
}

$conn = Database::getConnection();

if ($conn) {
    try {
        $stmtTopic = $conn->prepare("SELECT * FROM forum_topics WHERE id = :id LIMIT 1");
        $stmtTopic->bindParam(':id', $topicId);
        $stmtTopic->execute();
        $topic = $stmtTopic->fetch(PDO::FETCH_ASSOC);

        if (!$topic) {
            header("Location: index.php");
            die();
        }

        $stmtCat = $conn->prepare("SELECT * FROM forum_categories WHERE id = :id LIMIT 1");
        $stmtCat->bindParam(':id', $topic['category_id']);
        $stmtCat->execute();
        $category = $stmtCat->fetch(PDO::FETCH_ASSOC);

        if ($category['is_admin_only'] == 1 && $_SESSION['account'] !== 'merhel') {
            header("Location: topic.php?id=" . $topicId . "&err=" . urlencode("You do not have permission to reply here."));
            die();
        }

        $author = $_SESSION['account'];

        $stmt = $conn->prepare("INSERT INTO forum_posts (topic_id, content, author) VALUES (:topic_id, :content, :author)");
        $stmt->bindParam(':topic_id', $topicId);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $author);
        $stmt->execute();

        header("Location: topic.php?id=" . $topicId);
        die();

    } catch (\PDOException $e) {
        header("Location: topic.php?id=" . $topicId . "&err=" . urlencode("Database error occurred while posting."));
        die();
    }
} else {
    header("Location: topic.php?id=" . $topicId . "&err=" . urlencode("Database connection failed."));
    die();
}
