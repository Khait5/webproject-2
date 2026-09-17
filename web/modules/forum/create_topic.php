<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

if (!isset($_SESSION['account'])) {
    header("Location: ../auth/login.php");
    die();
}

$conn = Database::getConnection();
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : (isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0);
$category = null;

if ($conn && $categoryId > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM forum_categories WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            header("Location: index.php");
            die();
        }

        if ($category['is_admin_only'] == 1 && $_SESSION['account'] !== 'merhel') {
            header("Location: category.php?id=" . $categoryId);
            die();
        }
    } catch (\PDOException $e) {
        die("Database error.");
    }
} else {
    header("Location: index.php");
    die();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !Session::validateCsrfToken($_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $author = $_SESSION['account'];

        if (empty($title) || empty($content)) {
            $error = "Title and content are required.";
        } else {
            try {
                $conn->beginTransaction();

                $stmtTopic = $conn->prepare("INSERT INTO forum_topics (category_id, title, author) VALUES (:cat_id, :title, :author)");
                $stmtTopic->bindParam(':cat_id', $categoryId);
                $stmtTopic->bindParam(':title', $title);
                $stmtTopic->bindParam(':author', $author);
                $stmtTopic->execute();

                $topicId = $conn->lastInsertId();

                $stmtPost = $conn->prepare("INSERT INTO forum_posts (topic_id, content, author) VALUES (:topic_id, :content, :author)");
                $stmtPost->bindParam(':topic_id', $topicId);
                $stmtPost->bindParam(':content', $content);
                $stmtPost->bindParam(':author', $author);
                $stmtPost->execute();

                $conn->commit();

                header("Location: topic.php?id=" . $topicId);
                die();
            } catch (\PDOException $e) {
                $conn->rollBack();
                $error = "Failed to create topic.";
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
    <title>Create Topic - Ancardia</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; color: var(--gold); margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; background: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; }
        .form-group textarea { height: 200px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <div id="google_translate_element" style="position: absolute; right: 20px; top: 10px;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'es,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            <div class="logo">
                <a href="../../index.php"><img src="../../assets/img/psd/Ancardia.png" alt="Ancardia Logo"></a>
            </div>
            <nav class="top-nav">
                <a href="../../index.php">Home</a>
                <a href="index.php" style="color: var(--gold);">Forum</a>
                <?php if (!isset($_SESSION['account'])): ?><a href="../../modules/auth/register.php">Register</a><?php endif; ?>
                <a href="#">Donate</a>
                <a href="../../downloads.php">Files</a>
                <a href="../../modules/rankings/index.php">Rankings</a>
                <a href="#">About</a>
            </nav>
        </header>

        <div class="main-content">
            <!-- Left Sidebar -->
            <aside class="sidebar-left">
                <div class="panel">
                    <div class="panel-header">Navigation</div>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="../../index.php">Home</a></li>
                            <li><a href="index.php" style="border-color: var(--gold);">Forum</a></li>
                            <li><?php if (!isset($_SESSION['account'])): ?><a href="../../modules/auth/register.php">Register</a><?php endif; ?></li>
                            <li><a href="../../downloads.php">Files (Client)</a></li>
                            <li><a href="../../modules/rankings/index.php">Rankings</a></li>
                        </ul>
                    </nav>
                    <div class="panel-footer"></div>
                </div>
            </aside>

            <!-- Center Content -->
            <main class="content-center">
                <div class="panel">
                    <div class="panel-header">Create Topic in <?php echo htmlspecialchars($category['name']); ?></div>
                    <div style="padding: 15px;">

                        <?php if ($error): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form action="create_topic.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                            <input type="hidden" name="category_id" value="<?php echo $categoryId; ?>">

                            <div class="form-group">
                                <label for="title">Topic Title</label>
                                <input type="text" id="title" name="title" required maxlength="255">
                            </div>

                            <div class="form-group">
                                <label for="content">Initial Post</label>
                                <textarea id="content" name="content" required></textarea>
                            </div>

                            <button type="submit" class="btn">Create Topic</button>
                            <a href="category.php?id=<?php echo $categoryId; ?>" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
                        </form>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar-right">
                <div class="panel">
                    <div class="panel-header">Authorization</div>
                    <div style="padding: 10px;">
                        <?php if (!isset($_SESSION['account'])): ?>
                            <form action="../../modules/auth/login.php" method="POST" class="auth-form">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                <input type="text" name="login" placeholder="Login" autocomplete="username" required>
                                <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                                <button type="submit" class="btn" style="margin-top:10px;">Login</button>
                            </form>
                        <?php else: ?>
                            <div style="text-align:center;">
                                <p>Hello, <strong style="color:#fff;"><?php echo htmlspecialchars($_SESSION['account']); ?></strong>!</p>
                                <div class="auth-form" style="margin-top:15px;">
                                    <a href="../../modules/ucp/index.php" class="btn btn-secondary">User Control Panel</a>
                                    <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] > 0): ?>
                                        <a href="../../modules/admin/index.php" class="btn" style="background:#8b0000;">Admin Panel</a>
                                    <?php endif; ?>
                                    <a href="../../modules/auth/logout.php" class="btn">Logout</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </aside>
        </div>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Ancardia Server. All rights reserved.</p>
        </footer>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
