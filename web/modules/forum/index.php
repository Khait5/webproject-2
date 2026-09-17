<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../core/Session.php';

use Web\Config\Database;
use Web\Core\Session;

Session::start();

$conn = Database::getConnection();
$categories = [];

if ($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM forum_categories WHERE parent_id IS NULL ORDER BY id ASC");
        $rootCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rootCategories as $root) {
            $stmtSub = $conn->prepare("SELECT * FROM forum_categories WHERE parent_id = :id ORDER BY id ASC");
            $stmtSub->bindParam(':id', $root['id']);
            $stmtSub->execute();
            $root['subcategories'] = $stmtSub->fetchAll(PDO::FETCH_ASSOC);
            $categories[] = $root;
        }
    } catch (\PDOException $e) {
        $error = "Failed to load forum categories.";
    }
} else {
    $error = "Database connection failed.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Ancardia</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .forum-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .forum-table th, .forum-table td { padding: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); text-align: left; }
        .forum-table th { background: rgba(0, 0, 0, 0.5); color: var(--gold); }
        .forum-category-title { font-size: 16px; font-weight: bold; margin-bottom: 5px; display: block; color: #fff; text-decoration: none; }
        .forum-category-title:hover { color: var(--gold); }
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
                    <div class="panel-header">Community Forum</div>
                    <div style="padding: 15px;">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php foreach ($categories as $cat): ?>
                            <table class="forum-table">
                                <thead>
                                    <tr>
                                        <th><?php echo htmlspecialchars($cat['name']); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($cat['subcategories'])): ?>
                                        <tr><td>No subcategories found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($cat['subcategories'] as $sub): ?>
                                            <tr>
                                                <td>
                                                    <a href="category.php?id=<?php echo $sub['id']; ?>" class="forum-category-title"><?php echo htmlspecialchars($sub['name']); ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
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
                            <div style="text-align:center; margin-top:10px;">
                                <a href="../../modules/auth/register.php" style="font-size: 11px;">Register</a> |
                                <a href="#" style="font-size: 11px;">Forgot Password?</a>
                            </div>
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
