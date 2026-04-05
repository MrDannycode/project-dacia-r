<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// The Bouncer: Kick out logged-out users
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parola_veche = $_POST["parola_veche"] ?? "";
    $parola_noua = $_POST["parola_noua"] ?? "";
    $confirma_parola = $_POST["confirma_parola"] ?? "";

    if (!empty($parola_veche) && !empty($parola_noua) && !empty($confirma_parola)) {
        if ($parola_noua === $confirma_parola) {
            // Fetch current password hash
            $sql = "SELECT password_hash FROM utilizatori WHERE id_utilizator = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($parola_veche, $user["password_hash"])) {
                // Update password
                $new_hash = password_hash($parola_noua, PASSWORD_DEFAULT);
                $update_sql = "UPDATE utilizatori SET password_hash = :hash WHERE id_utilizator = :id";
                $update_stmt = $conn->prepare($update_sql);

                try {
                    $update_stmt->execute([
                        ':hash' => $new_hash,
                        ':id' => $user_id
                    ]);
                    $success_message = "Parola a fost schimbată cu succes!";
                } catch (PDOException $e) {
                    $error_message = "A apărut o eroare la actualizarea parolei.";
                }
            } else {
                $error_message = "Parola actuală este incorectă!";
            }
        } else {
            $error_message = "Noua parolă și confirmarea acesteia nu coincid!";
        }
    } else {
        $error_message = "Toate câmpurile sunt obligatorii!";
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Securitate Cont</h2>
            <p class="auth-subtitle">Schimbă-ți parola pentru a-ți menține contul în siguranță.</p>

            <?php if (!empty($success_message)): ?>
                <div class="auth-success" style="color: #1a7b45; background: #e6f4ea; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 500; font-size: 0.9rem;"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="auth-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="parola_veche">Parola actuală</label>
                    <input type="password" id="parola_veche" name="parola_veche" required placeholder="Introdu parola ta curentă">
                </div>

                <div class="form-group">
                    <label for="parola_noua">Parola nouă</label>
                    <input type="password" id="parola_noua" name="parola_noua" required placeholder="Alege o parolă nouă">
                </div>

                <div class="form-group">
                    <label for="confirma_parola">Confirmă parola nouă</label>
                    <input type="password" id="confirma_parola" name="confirma_parola" required placeholder="Reintrodu parola nouă">
                </div>
                
                <button type="submit" class="btn-auth">Schimbă Parola</button>
            </form>
            
            <div class="auth-links" style="margin-top: 20px;">
                <a href="cont.php" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; color: #555; text-decoration: none; font-weight: 600; padding: 10px 20px; border-radius: 8px; background: #f0f0f0; transition: all 0.3s ease;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Înapoi la cont
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
