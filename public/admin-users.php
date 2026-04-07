<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], ['user_admin', 'super_admin'])) {
    header("Location: index.php");
    exit();
}

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    
    if ($act === 'edit') {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role_user'] ?? 'customer'; // role from form
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'Numele, prenumele și emailul sunt obligatorii!';
        } else {
            $id = $_POST['id_utilizator'] ?? 0;
            // Daca este si parola setata, o actualizam, altfel ramane aceeasi
            $password_input = $_POST['password'] ?? '';
            
            if (!empty($password_input)) {
                $password_hash = password_hash($password_input, PASSWORD_DEFAULT);
                $sql = "UPDATE utilizatori SET first_name = :first_name, last_name = :last_name, email = :email, role = :role, password_hash = :password_hash WHERE id_utilizator = :id";
                $params = [
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':email' => $email,
                    ':role' => $role,
                    ':password_hash' => $password_hash,
                    ':id' => $id
                ];
            } else {
                $sql = "UPDATE utilizatori SET first_name = :first_name, last_name = :last_name, email = :email, role = :role WHERE id_utilizator = :id";
                $params = [
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':email' => $email,
                    ':role' => $role,
                    ':id' => $id
                ];
            }
            
            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                $success = 'Utilizatorul a fost actualizat cu succes!';
                $action = 'list';
            } catch (PDOException $e) {
                if ($e->getCode() == '23505') { // Unique violation
                    $error = 'Acest email este deja folosit!';
                } else {
                    $error = 'Eroare la actualizare: ' . $e->getMessage();
                }
            }
        }
    } elseif ($act === 'delete') {
         $id = $_POST['id_utilizator'] ?? 0;
         if ($id == $_SESSION['user_id']) {
             $error = "Nu te poți șterge pe tine însuți!";
         } else {
             try {
                 $sql = "DELETE FROM utilizatori WHERE id_utilizator = :id";
                 $stmt = $conn->prepare($sql);
                 $stmt->execute([':id' => $id]);
                 $success = 'Utilizatorul a fost șters!';
             } catch (PDOException $e) {
                 if ($e->getCode() == '23503') { // Foreign key constraint
                     $error = 'Acest utilizator are comenzi asociate și nu poate fi șters!';
                 } else {
                     $error = 'Eroare la ștergere: ' . $e->getMessage();
                 }
             }
         }
         $action = 'list';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="assets/css/admin-super.css?v=<?= time() ?>">

<div class="sa-wrapper" style="max-width: 900px; padding-bottom: 0; margin-bottom: 0;">
    <div class="sa-page-header" style="margin-bottom: 0;">
        <div class="sa-page-header-left">
            <span class="sa-badge" style="background: linear-gradient(135deg, #74b9ff, #0984e3);">Admin Utilizatori</span>
            <h2>Administrare Utilizatori</h2>
            <p>Gestionează conturile și rolurile utilizatorilor pe platforma Dacia.</p>
        </div>
        <div class="sa-page-header-right">
            <img src="assets/images/logo.jpg" alt="Dacia Logo" class="sa-logo">
        </div>
    </div>
</div>

<div class="content" style="margin-top: 1.5rem;">
    <?php if ($error): ?><p style="color:red; background: #ffe6e6; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green; background: #e6ffe6; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <?php if ($action === 'list'): ?>
        <table border="1" style="width: 100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th>ID</th>
                    <th>Nume</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT id_utilizator, first_name, last_name, email, role FROM utilizatori ORDER BY id_utilizator DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $numeS = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                    $roleS = htmlspecialchars($row['role']);
                    echo "<tr>";
                    echo "<td>" . $row['id_utilizator'] . "</td>";
                    echo "<td><strong>" . $numeS . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . $roleS . "</td>";
                    echo "<td>
                            <a href='admin-users.php?action=edit&id=" . $row['id_utilizator'] . "' style='color: #0984e3; text-decoration: none; font-weight: bold;'>Edit</a> | 
                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Sigur ștergi acest utilizator?\");'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='id_utilizator' value='" . $row['id_utilizator'] . "'>
                                <button type='submit' style='background:none; border:none; color:#d63031; cursor:pointer; font-weight: bold; font-family: inherit;'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php elseif ($action === 'edit'): 
        $user_row = ['first_name' => '', 'last_name' => '', 'email' => '', 'role' => 'customer'];
        $id = $_GET['id'] ?? 0;
        $stmt = $conn->prepare("SELECT * FROM utilizatori WHERE id_utilizator = :id");
        $stmt->execute([':id' => $id]);
        $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user_row) {
            echo "<p>Utilizatorul nu a fost găsit!</p>";
            $action = 'list';
        } else {
    ?>
        <form method="POST" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_utilizator" value="<?= $id ?>">
            
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Prenume (first_name):</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user_row['first_name']) ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required>
            </p>
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nume (last_name):</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user_row['last_name']) ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required>
            </p>
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user_row['email']) ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required>
            </p>
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Schimbare Parolă (lasă gol pentru a o păstra):</label>
                <input type="password" name="password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Parolă nouă">
            </p>
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Rol:</label>
                <select name="role_user" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="customer" <?= $user_row['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                    <option value="car_admin" <?= $user_row['role'] === 'car_admin' ? 'selected' : '' ?>>Car Admin</option>
                    <option value="user_admin" <?= $user_row['role'] === 'user_admin' ? 'selected' : '' ?>>User Admin</option>
                    <option value="news_admin" <?= $user_row['role'] === 'news_admin' ? 'selected' : '' ?>>News Admin</option>
                    <option value="super_admin" <?= $user_row['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                </select>
            </p>
            <div style="margin-top: 20px;">
                <button type="submit" class="btn" style="border:none; cursor:pointer; font-size:16px;">
                    Salvează Modificări
                </button>
                <a href="admin-users.php" style="margin-left: 15px; color: #636e72; text-decoration: none;">Renunță</a>
            </div>
        </form>
    <?php } endif; ?>
</div>
<style>
.content {
    max-width: 900px;
    margin: 2rem auto;
    padding: 2rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.content table th, .content table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #0984e3;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background-color 0.2s;
}
.btn:hover {
    background-color: #74b9ff;
}
</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
