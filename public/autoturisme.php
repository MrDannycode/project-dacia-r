<?php
session_start(); // This MUST be the very first thing

// Check if user is logged in (optional, if you want this page restricted)
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include __DIR__ . '/includes/header.php';
?>
<h1>Autoturisme</h1>
<p>Lista modelelor disponibile:</p>
<!-- Future dynamic PHP loop here -->
<?php include __DIR__ . '/includes/footer.php'; ?>