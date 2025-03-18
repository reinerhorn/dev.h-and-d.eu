
<?php
session_start();
require_once 'config.php';

$role = $_SESSION['role'] ?? NULL;
$navs = $conn->query("SELECT name, link FROM navigation WHERE role IS NULL OR role = $role");
?>

<nav>
    <ul>
        <?php while ($nav = $navs->fetch_assoc()): ?>
            <li><a href="<?= $nav['link'] ?>"><?= $nav['name'] ?></a></li>
        <?php endwhile; ?>
    </ul>
</nav>