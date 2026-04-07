<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$prefix = (isset($is_subdir) && $is_subdir) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dacia | Auto Website</title>
    <link rel="stylesheet" href="<?= $prefix ?>assets/css/style.css?v=<?= time() ?>">
</head>

<body>
    <header class="site-header">
        <div class="header-top">
            <div class="header-top-inner">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <span>Bun venit, <?= htmlspecialchars($_SESSION["user_name"]) ?></span>
                    <a href="<?= $prefix ?>logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?= $prefix ?>login.php">Login</a>
                    <a href="<?= $prefix ?>logregister.php">Înregistrare</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-main">
            <h1 class="site-title"><a href="<?= $prefix ?>index.php"><img src="<?= $prefix ?>assets/images/logo.jpg"
                        alt="Dacia"></a></h1>
            <nav class="site-nav">
                <a href="<?= $prefix ?>index.php">Acasă</a>
                <a href="<?= $prefix ?>nav-autoturisme.php">Autoturisme</a>
                <a href="<?= $prefix ?>nav-oferte-standard.php">Nivele echipare standard</a>
                <a href="<?= $prefix ?>nav-news.php">News</a>
                <a href="<?= $prefix ?>nav-harta.php">Harta reprezentanțe</a>
                <a href="<?= $prefix ?>account.php">My Dacia</a>
                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === 'news_admin'): ?>
                    <a href="<?= $prefix ?>admin-stiri.php" style="margin-left: auto;">Panou Admin Știri</a>
                <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === 'car_admin'): ?>
                    <a href="<?= $prefix ?>admin-masini.php" style="margin-left: auto;">Panou Admin Mașini</a>
                <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === 'user_admin'): ?>
                    <a href="<?= $prefix ?>admin-users.php" style="margin-left: auto;">Panou Admin Utilizatori</a>
                <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === 'super_admin'): ?>
                    <a href="<?= $prefix ?>admin-super.php" style="margin-left: auto;">Panou Super Admin</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>