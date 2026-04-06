<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nume = trim($_POST["nume"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $parola_raw = $_POST["parola"] ?? "";

    if (!empty($nume) && !empty($email) && !empty($parola_raw)) {
        $parola = password_hash($parola_raw, PASSWORD_DEFAULT);
        
        $nume_parts = explode(' ', $nume, 2);
        $first_name = $nume_parts[0];
        $last_name = $nume_parts[1] ?? '';

        $sql = "INSERT INTO utilizatori (first_name, last_name, email, password_hash) VALUES (:first_name, :last_name, :email, :password_hash)";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':password_hash' => $parola
            ]);

            header("Location: login.php");
            exit();

        } catch (PDOException $e) {
            $error_message = "Adresa de email există deja!";
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
            <h2 class="auth-title">Creare cont</h2>
            <p class="auth-subtitle">Bun venit! Completează datele de mai jos pentru a te înregistra.</p>

            <?php if (!empty($error_message)): ?>
                <div class="auth-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="nume">Nume complet</label>
                    <input type="text" id="nume" name="nume" value="<?= htmlspecialchars($_POST['nume'] ?? '') ?>" required placeholder="Numele tău">
                </div>

                <div class="form-group">
                    <label for="email">Adresă de email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required placeholder="nume@exemplu.com">
                </div>
                
                <div class="form-group">
                    <label for="parola">Parolă</label>
                    <input type="password" id="parola" name="parola" required placeholder="Alege o parolă sigură">
                </div>
                
                <button type="submit" class="btn-auth">Înregistrează-te</button>
            </form>
            
            <div class="auth-links">
                Ai deja un cont? <a href="login.php">Conectează-te</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>