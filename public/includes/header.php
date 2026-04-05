<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dacia | Auto Website</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
</head>

<body>
    <header class="site-header">
        <div class="header-top">
            <div class="header-top-inner">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <span>Bun venit, <?= htmlspecialchars($_SESSION["user_name"]) ?></span>
                    <?php if (isset($_SESSION["role"]) && in_array($_SESSION["role"], ['news_admin', 'super_admin'])): ?>
                        <a href="admin_stiri.php" style="color:red; font-weight:bold;">Administrează Știrile</a>
                    <?php endif; ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Înregistrare</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-main">
            <h1 class="site-title"><a href="index.php"><img src="assets/images/logo.jpg" alt="Dacia"></a></h1>
            <nav class="site-nav">
                <a href="index.php">Acasă</a>
                <a href="autoturisme.php">Autoturisme</a>
                <a href="oferte_standard.php">Nivele echipare standard</a>
                <a href="news.php">Știri</a>
                <a href="harta.php">Harta reprezentanțe</a>
                <a href="cont.php">My Dacia</a>
            </nav>
        </div>
    </header>
    <main>