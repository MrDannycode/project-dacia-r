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

// Fetch current user data
$sql = "SELECT first_name, last_name, email FROM utilizatori WHERE id_utilizator = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: logout.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if (!empty($first_name) && !empty($last_name) && !empty($email)) {
        $update_sql = "UPDATE utilizatori SET first_name = :first_name, last_name = :last_name, email = :email WHERE id_utilizator = :id";
        $update_stmt = $conn->prepare($update_sql);
        
        try {
            $update_stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':id' => $user_id
            ]);
            
            $success_message = "Profilul a fost actualizat cu succes!";
            $user['first_name'] = $first_name;
            $user['last_name'] = $last_name;
            $user['email'] = $email;
            $_SESSION['user_name'] = trim($first_name . ' ' . $last_name);

        } catch (PDOException $e) {
            $error_message = "A apărut o eroare. Probabil adresa de email există deja.";
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
            <h2 class="auth-title">Editează Profilul</h2>
            <p class="auth-subtitle">Actualizează-ți informațiile personale.</p>

            <?php if (!empty($success_message)): ?>
                <div class="auth-success" style="color: #1a7b45; background: #e6f4ea; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 500; font-size: 0.9rem;"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="auth-error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="first_name">Prenume</label>
                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required placeholder="Prenumele tău">
                </div>

                <div class="form-group">
                    <label for="last_name">Nume</label>
                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required placeholder="Numele tău de familie">
                </div>

                <div class="form-group">
                    <label for="email">Adresă de email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required placeholder="nume@exemplu.com">
                </div>
                
                <button type="submit" class="btn-auth">Salvează Modificările</button>
            </form>
            
            <div class="auth-links" style="margin-top: 20px;">
                <a href="account.php" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; color: #555; text-decoration: none; font-weight: 600; padding: 10px 20px; border-radius: 8px; background: #f0f0f0; transition: all 0.3s ease;">
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
