<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $parola = $_POST["parola"] ?? "";

    if (!empty($email) && !empty($parola)) {
        $sql = "SELECT * FROM utilizatori WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($parola, $user["password_hash"])) {

            $_SESSION["user_id"] = $user["id_utilizator"];
            $_SESSION["user_name"] = trim($user["first_name"] . ' ' . $user["last_name"]);
            $_SESSION["role"] = $user["role"];

            header("Location: index.php");
            exit();

        } else {
            $error_message = "Adresa de email sau parola este incorectă!";
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
            <h2 class="auth-title">Autentificare cont</h2>
            <p class="auth-subtitle">Bine ai revenit! Introdu datele pentru a te autentifica.</p>

            <?php if (!empty($error_message)): ?>
                <div class="auth-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Adresă de email</label>
                    <input type="email" id="email" name="email" required placeholder="nume@exemplu.com">
                </div>
                
                <div class="form-group">
                    <label for="parola">Parolă</label>
                    <input type="password" id="parola" name="parola" required placeholder="Introdu parola ta">
                </div>
                
                <button type="submit" class="btn-auth">Conectare</button>
            </form>
            
            <div class="auth-links">
                Nu ai un cont încă? <a href="logregister.php">Înregistrează-te</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>