<?php
include "includes/header.php";


// The Bouncer: Kick out logged-out users
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<div class="account-container">
    <header class="account-header">
        <h1 class="page-title">Contul Meu</h1>
        <p class="page-subtitle">Gestionează datele personle, setările de securitate și preferințele tale.</p>
    </header>

    <div class="account-grid">
        
        <!-- Profile Info -->
        <div class="news-card">
            <div class="account-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3 class="account-card-title">Date Personale</h3>
            <p class="account-card-desc">
                Actualizează-ți informațiile personale și adresa de email asociată contului.
            </p>
            <a href="#" class="btn-account">Editează Profilul</a>
        </div>

        <!-- Security -->
        <div class="news-card">
            <div class="account-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h3 class="account-card-title">Securitate</h3>
            <p class="account-card-desc">
                Schimbă parola și gestionează setările de securitate pentru a-ți proteja contul.
            </p>
            <a href="#" class="btn-account">Setări Securitate</a>
        </div>

        <!-- Saved Cars -->
        <div class="news-card">
            <div class="account-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"></path>
                    <circle cx="7" cy="17" r="2"></circle>
                    <path d="M9 17h6"></path>
                    <circle cx="17" cy="17" r="2"></circle>
                </svg>
            </div>
            <h3 class="account-card-title">Configurări Salvate</h3>
            <p class="account-card-desc">
                Vezi lista de mașini și configurații pe care le-ai salvat anterior.
            </p>
            <a href="masinile-mele.php" class="btn-account">Vezi Mașinile</a>
        </div>

    </div>
    
    <div class="account-logout-wrapper">
        <a href="logout.php" class="btn-logout">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Deconectare
        </a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>