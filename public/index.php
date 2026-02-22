<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<?php include "includes/header.php"; ?>

<h2>Welcome to Auto Website</h2>
<p>This is the start.</p>

<?php include "includes/footer.php"; ?>
