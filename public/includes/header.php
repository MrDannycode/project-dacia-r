<?php if (isset($_SESSION["user_id"])): ?>
    Welcome, <?= $_SESSION["user_name"]; ?> |
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Login</a> |
    <a href="register.php">Sign Up</a>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Auto Website</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
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
