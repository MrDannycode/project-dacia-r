<?php
// Session is now handled by header.php!
include "includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<h2>Welcome to Auto Website</h2>
<p>This is the start.</p>

<?php include "includes/footer.php"; ?>
