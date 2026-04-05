<?php
session_start(); // This MUST be the very first thing

?>
<?php
include __DIR__ . '/includes/header.php';
?>
<h1>Autoturisme</h1>
<p>Lista modelelor disponibile:</p>
<div class="news-grid">
    <div class="news-card car-card">
        <h3 class="card-title-top">Sandero Stepway</h3>
        <img src="assets/images/sandero-mic.jpg" alt="Sandero Stepway">
        <div class="card-buttons">
            <a href="sandero-stepway.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
    <div class="news-card car-card">
        <h3 class="card-title-top">Bigster</h3>
        <img src="assets/images/bigster-mic.jpg" alt="Bigster">
        <div class="card-buttons">
            <a href="bigster.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
    <div class="news-card car-card">
        <h3 class="card-title-top">Duster</h3>
        <img src="assets/images/duster-mic.jpg" alt="Duster">
        <div class="card-buttons">
            <a href="duster.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
    <div class="news-card car-card">
        <h3 class="card-title-top">Jogger</h3>
        <img src="assets/images/jogger-mic.jpg" alt="Jogger">
        <div class="card-buttons">
            <a href="jogger.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
    <div class="news-card car-card">
        <h3 class="card-title-top">Logan</h3>
        <img src="assets/images/logan-mic.png" alt="Logan">
        <div class="card-buttons">
            <a href="logan.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
    <div class="news-card car-card">
        <h3 class="card-title-top">Sandero</h3>
        <img src="assets/images/sandero-mic.jpg" alt="Sandero">
        <div class="card-buttons">
            <a href="sandero.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
    <div class="news-card car-card">
        <h3 class="card-title-top">Spring</h3>
        <img src="assets/images/spring-micc.jpg" alt="Spring">
        <div class="card-buttons">
            <a href="spring.php" class="btn-account btn-small">Info</a>
            <a href="config-model.php" class="btn-account btn-small btn-outline">Configurare avansată</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>