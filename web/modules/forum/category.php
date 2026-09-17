<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();
$csrf_token = Session::generateCsrfToken();

$conn = Database::getConnection();
$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = null;
$topics = [];

if ($conn && $categoryId > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM forum_categories WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $categoryId);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            $stmtTopics = $conn->prepare("SELECT t.*, (SELECT COUNT(id) FROM forum_posts p WHERE p.topic_id = t.id) as post_count FROM forum_topics t WHERE t.category_id = :cat_id ORDER BY t.created_at DESC");
            $stmtTopics->bindParam(':cat_id', $categoryId);
            $stmtTopics->execute();
            $topics = $stmtTopics->fetchAll(PDO::FETCH_ASSOC);
        } else {
            header("Location: index.php");
            die();
        }
    } catch (\PDOException $e) {
        $error = "Failed to load topics.";
    }
} else {
    header("Location: index.php");
    die();
}

$canPost = false;
if (isset($_SESSION['account'])) {
    if ($category['is_admin_only'] == 0 || $_SESSION['account'] === 'merhel') {
        $canPost = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category ? htmlspecialchars($category['name']) : 'Forum'; ?> - Ancardia</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .forum-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .forum-table th, .forum-table td { padding: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); text-align: left; }
        .forum-table th { background: rgba(0, 0, 0, 0.5); color: var(--gold); }
        .forum-category-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; display: block; color: #fff; text-decoration: none; }
        .forum-category-title:hover { color: var(--gold); }
        .btn-post { float: right; margin-bottom: 15px; }
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
                    <div class="panel-header"><?php echo htmlspecialchars($category['name']); ?></div>
                    <div style="padding: 15px;">

                        <?php if ($canPost): ?>
                            <a href="create_topic.php?category_id=<?php echo $categoryId; ?>" class="btn btn-post">Create Topic</a>
                            <div style="clear:both;"></div>
                        <?php endif; ?>

                        <table class="forum-table">
                            <thead>
                                <tr>
                                    <th>Topic</th>
                                    <th>Author</th>
                                    <th>Replies</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($topics)): ?>
                                    <tr><td colspan="4" style="text-align:center;">No topics found in this category.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($topics as $topic): ?>
                                        <tr>
                                            <td><a href="topic.php?id=<?php echo $topic['id']; ?>" class="forum-category-title"><?php echo htmlspecialchars($topic['title']); ?></a></td>
                                            <td><?php echo htmlspecialchars($topic['author']); ?></td>
                                            <td><?php echo max(0, $topic['post_count'] - 1); ?></td>
                                            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($topic['created_at']))); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
