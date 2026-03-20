<?php
include "../includes/header.php"; 

// The Bouncer: Kick out logged-out users
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>
<h1>My Account</h1>
<p>Authentification and settings.</p>
<?php include __DIR__ . '/../includes/footer.php'; ?>