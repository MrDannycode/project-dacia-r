<?php 
// Start the session safely if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dacia Auto Project</title>
    <link rel="stylesheet" href="../public/assets/css/style.css"> <style>
        /* CSS rapid pentru Header */
        header.main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: #fff;
            padding: 15px 30px;
        }
        nav.nav-left a, nav.nav-right a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
        }
        nav.nav-right a {
            margin-right: 0;
            margin-left: 15px;
        }
    </style>
</head>
<body>

<header class="main-header">
    <nav class="nav-left">
        <a href="index.php">Home</a>
        <a href="autoturisme.php">Cars</a>
        <a href="config_model.php">Configure Car</a>
        <a href="oferte_standard.php">Standard Configurations</a>
        <a href="news.php">News</a>
    </nav>
    <nav class="nav-right">
        <a href="harta.php">Harta reprezentante</a> <a href="cont.php">My Dacia</a>
    </nav>
</header>