<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], ['car_admin', 'super_admin'])) {
    header("Location: index.php");
    exit();
}

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    
    if ($act === 'add' || $act === 'edit') {
        $nume_model = $_POST['nume_model'] ?? '';
        $tip_caroserie = $_POST['tip_caroserie'] ?? '';
        $numar_locuri = $_POST['numar_locuri'] ?? 5;
        
        if (empty($nume_model) || empty($tip_caroserie) || empty($numar_locuri)) {
            $error = 'Toate câmpurile sunt obligatorii!';
        } else {
            if ($act === 'add') {
                $sql = "INSERT INTO Masini (nume_model, tip_caroserie, numar_locuri) VALUES (:nume_model, :tip_caroserie, :numar_locuri)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':nume_model' => $nume_model,
                    ':tip_caroserie' => $tip_caroserie,
                    ':numar_locuri' => $numar_locuri
                ]);
                $success = 'Mașina a fost adăugată cu succes!';
                $action = 'list';
            } elseif ($act === 'edit') {
                $id = $_POST['id_masina'] ?? 0;
                $sql = "UPDATE Masini SET nume_model = :nume_model, tip_caroserie = :tip_caroserie, numar_locuri = :numar_locuri WHERE id_masina = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':nume_model' => $nume_model,
                    ':tip_caroserie' => $tip_caroserie,
                    ':numar_locuri' => $numar_locuri,
                    ':id' => $id
                ]);
                $success = 'Datele mașinii au fost actualizate cu succes!';
                $action = 'list';
            }
        }
    } elseif ($act === 'delete') {
         $id = $_POST['id_masina'] ?? 0;
         
         // In a real application, you might need to check for dependencies (like Versiuni) before deleting
         // For now, we will attempt to delete, and catch any foreign key constraint violation
         try {
             $sql = "DELETE FROM Masini WHERE id_masina = :id";
             $stmt = $conn->prepare($sql);
             $stmt->execute([':id' => $id]);
             $success = 'Mașina a fost ștearsă!';
         } catch (PDOException $e) {
             if ($e->getCode() == '23503') { // Foreign key constraint violation
                 $error = 'Nu poți șterge această mașină deoarece are versiuni sau comenzi asociate!';
             } else {
                 $error = 'A apărut o eroare la ștergere: ' . $e->getMessage();
             }
         }
         $action = 'list';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="assets/css/admin-super.css?v=<?= time() ?>">

<div class="sa-wrapper" style="max-width: 900px; padding-bottom: 0; margin-bottom: 0;">
    <!-- ── Page Header ─────────────────────────────── -->
    <div class="sa-page-header" style="margin-bottom: 0;">
        <div class="sa-page-header-left">
            <span class="sa-badge" style="background: linear-gradient(135deg, #55efc4, #00b894);">Admin Mașini</span>
            <h2>Administrare Mașini</h2>
            <p>Gestionează modelele auto disponibile pe platforma Dacia.</p>
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
        <a href="admin-masini.php?action=add" class="btn">Adaugă Mașină Nouă</a>
        <br><br>
        <table border="1" style="width: 100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th>ID</th>
                    <th>Nume Model</th>
                    <th>Tip Caroserie</th>
                    <th>Locuri</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM Masini ORDER BY id_masina DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_masina'] . "</td>";
                    echo "<td><strong>" . htmlspecialchars($row['nume_model']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($row['tip_caroserie']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['numar_locuri']) . "</td>";
                    echo "<td>
                            <a href='admin-masini.php?action=edit&id=" . $row['id_masina'] . "' style='color: #0984e3; text-decoration: none; font-weight: bold;'>Edit</a> | 
                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Sigur ștergi această mașină?\");'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='id_masina' value='" . $row['id_masina'] . "'>
                                <button type='submit' style='background:none; border:none; color:#d63031; cursor:pointer; font-weight: bold; font-family: inherit;'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php elseif ($action === 'add' || $action === 'edit'): 
        $masina = ['nume_model' => '', 'tip_caroserie' => '', 'numar_locuri' => 5];
        $id = 0;
        if ($action === 'edit') {
            $id = $_GET['id'] ?? 0;
            $stmt = $conn->prepare("SELECT * FROM Masini WHERE id_masina = :id");
            $stmt->execute([':id' => $id]);
            $masina = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$masina) {
                echo "<p>Mașina nu a fost găsită!</p>";
                $action = 'list';
            }
        }
        if ($action !== 'list'):
    ?>
        <form method="POST" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <input type="hidden" name="action" value="<?= $action ?>">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="id_masina" value="<?= $id ?>">
            <?php endif; ?>
            
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nume Model:</label>
                <input type="text" name="nume_model" value="<?= htmlspecialchars($masina['nume_model']) ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required placeholder="ex: Duster">
            </p>
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Tip Caroserie:</label>
                <input type="text" name="tip_caroserie" value="<?= htmlspecialchars($masina['tip_caroserie']) ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required placeholder="ex: SUV, Hatchback">
            </p>
            <p>
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Număr Locuri:</label>
                <input type="number" name="numar_locuri" value="<?= htmlspecialchars($masina['numar_locuri']) ?>" style="width: 100px; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" required min="2" max="9">
            </p>
            <div style="margin-top: 20px;">
                <button type="submit" class="btn" style="border:none; cursor:pointer; font-size:16px;">
                    <?= $action === 'add' ? 'Adaugă Mașina' : 'Salvează Modificări' ?>
                </button>
                <a href="admin-masini.php" style="margin-left: 15px; color: #636e72; text-decoration: none;">Renunță</a>
            </div>
        </form>
    <?php endif; endif; ?>
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
    background-color: #00b894;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: background-color 0.2s;
}
.btn:hover {
    background-color: #00a8ff;
}
</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
