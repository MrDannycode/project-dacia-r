<?php 
// Start the session safely if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Auto Website</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div style="text-align: right; padding: 10px;">
        <?php if (isset($_SESSION["user_id"])): ?>
            Welcome, <?= htmlspecialchars($_SESSION["user_name"]); ?> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> |
            <a href="register.php">Sign Up</a>
        <?php endif; ?>
    </div>
    
    <h1>Auto Website</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="autoturisme.php">Autoturisme</a>
        <a href="config_model.php">Configureaza model</a>
        <a href="oferte_standard.php">Nivele echipare standard</a>
        <a href="harta.php">Harta reprezentante</a>
        <a href="cont.php">Cont</a>

    </nav>
</header>
