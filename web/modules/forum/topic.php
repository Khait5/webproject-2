<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

$conn = Database::getConnection();
$topicId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$topic = null;
$category = null;
$posts = [];

if ($conn && $topicId > 0) {
    try {
        $stmt = $conn->prepare("SELECT * FROM forum_topics WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $topicId);
        $stmt->execute();
        $topic = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($topic) {
            $stmtCat = $conn->prepare("SELECT * FROM forum_categories WHERE id = :id LIMIT 1");
            $stmtCat->bindParam(':id', $topic['category_id']);
            $stmtCat->execute();
            $category = $stmtCat->fetch(PDO::FETCH_ASSOC);

            $stmtPosts = $conn->prepare("SELECT * FROM forum_posts WHERE topic_id = :topic_id ORDER BY created_at ASC");
            $stmtPosts->bindParam(':topic_id', $topicId);
            $stmtPosts->execute();
            $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);
        } else {
            header("Location: index.php");
            die();
        }
    } catch (\PDOException $e) {
        $error = "Failed to load topic.";
    }
} else {
    header("Location: index.php");
    die();
}

$canReply = false;
if (isset($_SESSION['account'])) {
    if ($category['is_admin_only'] == 0 || $_SESSION['account'] === 'merhel') {
        $canReply = true;
    }
}
$csrf_token = Session::generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $topic ? htmlspecialchars($topic['title']) : 'Topic'; ?> - Ancardia</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .post-container { border: 1px solid rgba(255, 255, 255, 0.1); margin-bottom: 20px; background: rgba(0, 0, 0, 0.3); }
        .post-header { background: rgba(0, 0, 0, 0.5); padding: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); color: var(--gold); }
        .post-body { padding: 15px; display: flex; }
        .post-author { width: 150px; border-right: 1px solid rgba(255, 255, 255, 0.1); padding-right: 15px; text-align: center; }
        .post-content { padding-left: 15px; flex-grow: 1; white-space: pre-wrap; line-height: 1.5; color: #ddd; }
        .reply-form { margin-top: 30px; }
        .reply-form textarea { width: 100%; height: 150px; padding: 10px; background: rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; margin-bottom: 10px; }
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
                    <div class="panel-header"><?php echo htmlspecialchars($topic['title']); ?></div>
                    <div style="padding: 15px;">
                        <div style="margin-bottom: 15px;">
                            <a href="category.php?id=<?php echo $category['id']; ?>" style="color: #4da6ff;">&laquo; Back to <?php echo htmlspecialchars($category['name']); ?></a>
                        </div>

                        <?php if (isset($_GET['err'])): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['err']); ?></div>
                        <?php endif; ?>

                        <?php foreach ($posts as $post): ?>
                            <div class="post-container">
                                <div class="post-header">
                                    Posted on <?php echo htmlspecialchars(date('M j, Y H:i', strtotime($post['created_at']))); ?>
                                </div>
                                <div class="post-body">
                                    <div class="post-author">
                                        <strong><?php echo htmlspecialchars($post['author']); ?></strong>
                                    </div>
                                    <div class="post-content"><?php echo htmlspecialchars($post['content']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if ($canReply): ?>
                            <div class="reply-form">
                                <h3 style="color: var(--gold); margin-bottom: 10px;">Post a Reply</h3>
                                <form action="create_post.php" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                                    <input type="hidden" name="topic_id" value="<?php echo $topicId; ?>">
                                    <textarea name="content" required placeholder="Write your reply here..."></textarea>
                                    <button type="submit" class="btn">Reply</button>
                                </form>
                            </div>
                        <?php elseif (!isset($_SESSION['account'])): ?>
                            <div style="text-align:center; padding: 20px; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);">
                                You must be logged in to reply. <a href="../../modules/auth/login.php" style="color: #4da6ff;">Login</a>
                            </div>
                        <?php endif; ?>
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
