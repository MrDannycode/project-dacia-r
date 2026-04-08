<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], ['car_admin', 'super_admin'])) {
    header("Location: index.php");
    exit();
}

$tab = $_GET['tab'] ?? 'masini';
$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    
    // --- MASINI ---
    if ($act === 'add_masina' || $act === 'edit_masina') {
        $nume_model = $_POST['nume_model'] ?? '';
        $tip_caroserie = $_POST['tip_caroserie'] ?? '';
        $numar_locuri = $_POST['numar_locuri'] ?? 5;
        
        if (empty($nume_model) || empty($tip_caroserie) || empty($numar_locuri)) {
            $error = 'Toate câmpurile sunt obligatorii!';
        } else {
            if ($act === 'add_masina') {
                $sql = "INSERT INTO Masini (nume_model, tip_caroserie, numar_locuri) VALUES (:nume_model, :tip_caroserie, :numar_locuri)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':nume_model' => $nume_model, ':tip_caroserie' => $tip_caroserie, ':numar_locuri' => $numar_locuri]);
                $success = 'Mașina a fost adăugată cu succes!';
                $action = 'list';
            } else {
                $id = $_POST['id_masina'] ?? 0;
                $sql = "UPDATE Masini SET nume_model = :nume_model, tip_caroserie = :tip_caroserie, numar_locuri = :numar_locuri WHERE id_masina = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':nume_model' => $nume_model, ':tip_caroserie' => $tip_caroserie, ':numar_locuri' => $numar_locuri, ':id' => $id]);
                $success = 'Datele mașinii au fost actualizate cu succes!';
                $action = 'list';
            }
        }
    } elseif ($act === 'delete_masina') {
         $id = $_POST['id_masina'] ?? 0;
         try {
             $stmt = $conn->prepare("DELETE FROM Masini WHERE id_masina = :id");
             $stmt->execute([':id' => $id]);
             $success = 'Mașina a fost ștearsă!';
         } catch (PDOException $e) {
             $error = ($e->getCode() == '23503') ? 'Nu poți șterge deoarece are versiuni asociate!' : 'Eroare: ' . $e->getMessage();
         }
         $action = 'list';
    }

    // --- MOTORIZARI ---
    elseif ($act === 'add_motor' || $act === 'edit_motor') {
        $data = [
            ':nume' => $_POST['nume_motor'] ?? '',
            ':combustibil' => $_POST['tip_combustibil'] ?? '',
            ':putere' => $_POST['putere_cp'] ?? 0,
            ':transmisie' => $_POST['transmisie'] ?? '',
            ':tractiune' => $_POST['tractiune'] ?? '',
            ':cilindree' => $_POST['capacitate_cilindrica_cm3'] ?? NULL,
            ':eco' => $_POST['tip_electrificare'] ?? NULL
        ];

        if ($act === 'add_motor') {
            $sql = "INSERT INTO Motorizari (nume_motor, tip_combustibil, putere_cp, transmisie, tractiune, capacitate_cilindrica_cm3, tip_electrificare) 
                    VALUES (:nume, :combustibil, :putere, :transmisie, :tractiune, :cilindree, :eco)";
            $conn->prepare($sql)->execute($data);
            $success = 'Motorizarea a fost adăugată!';
        } else {
            $data[':id'] = $_POST['id_motorizare'] ?? 0;
            $sql = "UPDATE Motorizari SET nume_motor=:nume, tip_combustibil=:combustibil, putere_cp=:putere, transmisie=:transmisie, tractiune=:tractiune, 
                    capacitate_cilindrica_cm3=:cilindree, tip_electrificare=:eco WHERE id_motorizare=:id";
            $conn->prepare($sql)->execute($data);
            $success = 'Motorizarea a fost actualizată!';
        }
        $action = 'list';
    } elseif ($act === 'delete_motor') {
        $id = $_POST['id_motorizare'] ?? 0;
        try {
            $conn->prepare("DELETE FROM Motorizari WHERE id_motorizare = :id")->execute([':id' => $id]);
            $success = 'Motorizarea a fost ștearsă!';
        } catch (PDOException $e) {
            $error = ($e->getCode() == '23503') ? 'Nu poți șterge deoarece este folosită în versiuni!' : 'Eroare: ' . $e->getMessage();
        }
        $action = 'list';
    }

    // --- VERSIUNI ---
    elseif ($act === 'add_versiune' || $act === 'edit_versiune') {
        $vid = $_POST['id_versiune'] ?? 0;
        $data = [
            ':id_m' => $_POST['id_masina'] ?? 0,
            ':id_mot' => $_POST['id_motorizare'] ?? 0,
            ':nivel' => $_POST['nivel_echipare'] ?? '',
            ':pret' => $_POST['pret_euro'] ?? 0
        ];

        if ($act === 'add_versiune') {
            $sql = "INSERT INTO Versiuni (id_masina, id_motorizare, nivel_echipare, pret_euro) VALUES (:id_m, :id_mot, :nivel, :pret)";
            $conn->prepare($sql)->execute($data);
            $success = 'Versiunea a fost adăugată!';
        } else {
            $data[':id'] = $vid;
            $sql = "UPDATE Versiuni SET id_masina=:id_m, id_motorizare=:id_mot, nivel_echipare=:nivel, pret_euro=:pret WHERE id_versiune=:id";
            $conn->prepare($sql)->execute($data);
            $success = 'Versiunea a fost actualizată!';
        }
        $action = 'list';
    } elseif ($act === 'delete_versiune') {
        $id = $_POST['id_versiune'] ?? 0;
        $conn->prepare("DELETE FROM Versiuni WHERE id_versiune = :id")->execute([':id' => $id]);
        $success = 'Versiunea a fost ștearsă!';
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
            <p>Gestionează modelele, motorizările și versiunile Dacia.</p>
        </div>
        <div class="sa-page-header-right">
            <img src="assets/images/logo.jpg" alt="Dacia Logo" class="sa-logo">
        </div>
    </div>

    <!-- ── Tab Navigation ──────────────────────────── -->
    <div class="sa-tabs" style="display: flex; gap: 10px; margin-top: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
        <a href="admin-masini.php?tab=masini" class="tab-btn <?= $tab === 'masini' ? 'active' : '' ?>">Modele (Mașini)</a>
        <a href="admin-masini.php?tab=motorizari" class="tab-btn <?= $tab === 'motorizari' ? 'active' : '' ?>">Motorizări</a>
        <a href="admin-masini.php?tab=versiuni" class="tab-btn <?= $tab === 'versiuni' ? 'active' : '' ?>">Versiuni (Configurații)</a>
    </div>
</div>

<div class="content" style="margin-top: 1.5rem;">
    <?php if ($error): ?><p style="color:red; background: #ffe6e6; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green; background: #e6ffe6; padding: 10px; border-radius: 4px;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <?php 
    // =========================================================================
    // TAB: MASINI
    // =========================================================================
    if ($tab === 'masini'): 
        if ($action === 'list'): ?>
            <a href="admin-masini.php?tab=masini&action=add" class="btn">Adaugă Mașină Nouă</a>
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
                                <a href='admin-masini.php?tab=masini&action=edit&id=" . $row['id_masina'] . "' class='edit-link'>Edit</a> | 
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Sigur ștergi?\");'>
                                    <input type='hidden' name='action' value='delete_masina'>
                                    <input type='hidden' name='id_masina' value='" . $row['id_masina'] . "'>
                                    <button type='submit' class='delete-btn'>Delete</button>
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
            }
        ?>
            <h3><?= $action === 'add' ? 'Adaugă Mașină' : 'Editează Mașină' ?></h3>
            <form method="POST" class="admin-form">
                <input type="hidden" name="action" value="<?= $action ?>_masina">
                <?php if ($action === 'edit'): ?><input type="hidden" name="id_masina" value="<?= $id ?>"><?php endif; ?>
                <p><label>Nume Model:</label><input type="text" name="nume_model" value="<?= htmlspecialchars($masina['nume_model']) ?>" required></p>
                <p><label>Tip Caroserie:</label><input type="text" name="tip_caroserie" value="<?= htmlspecialchars($masina['tip_caroserie']) ?>" required></p>
                <p><label>Locuri:</label><input type="number" name="numar_locuri" value="<?= htmlspecialchars($masina['numar_locuri']) ?>" required></p>
                <button type="submit" class="btn"><?= $action === 'add' ? 'Adaugă' : 'Salvează' ?></button>
                <a href="admin-masini.php?tab=masini">Anulează</a>
            </form>
        <?php endif; ?>

    <?php 
    // =========================================================================
    // TAB: MOTORIZARI
    // =========================================================================
    elseif ($tab === 'motorizari'): 
        if ($action === 'list'): ?>
            <a href="admin-masini.php?tab=motorizari&action=add" class="btn">Adaugă Motorizare</a>
            <br><br>
            <table border="1" style="width: 100%; border-collapse: collapse; background: #fff;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th>Nume Motor</th>
                        <th>Tip</th>
                        <th>Putere</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT * FROM Motorizari ORDER BY nume_motor");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['nume_motor']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['tip_combustibil']) . " (" . ($row['tip_electrificare'] ?? 'Non-eco') . ")</td>";
                        echo "<td>" . htmlspecialchars($row['putere_cp']) . " CP</td>";
                        echo "<td>
                                <a href='admin-masini.php?tab=motorizari&action=edit&id=" . $row['id_motorizare'] . "' class='edit-link'>Edit</a> | 
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Sigur ștergi?\");'>
                                    <input type='hidden' name='action' value='delete_motor'>
                                    <input type='hidden' name='id_motorizare' value='" . $row['id_motorizare'] . "'>
                                    <button type='submit' class='delete-btn'>Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php elseif ($action === 'add' || $action === 'edit'): 
            $m = ['nume_motor'=>'','tip_combustibil'=>'','putere_cp'=>0,'transmisie'=>'','tractiune'=>'','capacitate_cilindrica_cm3'=>'','tip_electrificare'=>''];
            $id = 0;
            if ($action === 'edit') {
                $id = $_GET['id'] ?? 0;
                $stmt = $conn->prepare("SELECT * FROM Motorizari WHERE id_motorizare = :id");
                $stmt->execute([':id' => $id]);
                $m = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        ?>
            <h3><?= $action === 'add' ? 'Adaugă Motorizare' : 'Editează Motorizare' ?></h3>
            <form method="POST" class="admin-form">
                <input type="hidden" name="action" value="<?= $action ?>_motor">
                <?php if ($action === 'edit'): ?><input type="hidden" name="id_motorizare" value="<?= $id ?>"><?php endif; ?>
                <p><label>Nume Motor:</label><input type="text" name="nume_motor" value="<?= htmlspecialchars($m['nume_motor']) ?>" required></p>
                <p><label>Combustibil:</label><input type="text" name="tip_combustibil" value="<?= htmlspecialchars($m['tip_combustibil']) ?>" required></p>
                <p><label>Putere (CP):</label><input type="number" name="putere_cp" value="<?= htmlspecialchars($m['putere_cp']) ?>" required></p>
                <p><label>Transmisie:</label><input type="text" name="transmisie" value="<?= htmlspecialchars($m['transmisie']) ?>" required></p>
                <p><label>Tracțiune:</label><input type="text" name="tractiune" value="<?= htmlspecialchars($m['tractiune']) ?>" required></p>
                <p><label>Cilindree (cm3):</label><input type="number" name="capacitate_cilindrica_cm3" value="<?= htmlspecialchars($m['capacitate_cilindrica_cm3']) ?>"></p>
                <p><label>Electrificare:</label><input type="text" name="tip_electrificare" value="<?= htmlspecialchars($m['tip_electrificare']) ?>"></p>
                <button type="submit" class="btn">Salvează</button>
                <a href="admin-masini.php?tab=motorizari">Anulează</a>
            </form>
        <?php endif; ?>

    <?php 
    // =========================================================================
    // TAB: VERSIUNI
    // =========================================================================
    elseif ($tab === 'versiuni'): 
        if ($action === 'list'): ?>
            <a href="admin-masini.php?tab=versiuni&action=add" class="btn">Adaugă Versiune Nouă</a>
            <br><br>
            <table border="1" style="width: 100%; border-collapse: collapse; background: #fff;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th>Model</th>
                        <th>Nivel Echipare</th>
                        <th>Motorizare</th>
                        <th>Preț (€)</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT v.*, ma.nume_model, mo.nume_motor, mo.putere_cp 
                            FROM Versiuni v 
                            JOIN Masini ma ON v.id_masina = ma.id_masina 
                            JOIN Motorizari mo ON v.id_motorizare = mo.id_motorizare 
                            ORDER BY ma.nume_model, v.pret_euro";
                    $stmt = $conn->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nume_model']) . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['nivel_echipare']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($row['nume_motor']) . " (" . $row['putere_cp'] . " CP)</td>";
                        echo "<td>" . number_format($row['pret_euro'], 2) . " €</td>";
                        echo "<td>
                                <a href='admin-masini.php?tab=versiuni&action=edit&id=" . $row['id_versiune'] . "' class='edit-link'>Edit</a> | 
                                <form method='POST' style='display:inline;' onsubmit='return confirm(\"Sigur ștergi?\");'>
                                    <input type='hidden' name='action' value='delete_versiune'>
                                    <input type='hidden' name='id_versiune' value='" . $row['id_versiune'] . "'>
                                    <button type='submit' class='delete-btn'>Delete</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php elseif ($action === 'add' || $action === 'edit'): 
            $v = ['id_masina' => 0, 'id_motorizare' => 0, 'nivel_echipare' => '', 'pret_euro' => 0];
            $id = 0;
            if ($action === 'edit') {
                $id = $_GET['id'] ?? 0;
                $stmt = $conn->prepare("SELECT * FROM Versiuni WHERE id_versiune = :id");
                $stmt->execute([':id' => $id]);
                $v = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            $lista_masini = $conn->query("SELECT id_masina, nume_model FROM Masini ORDER BY nume_model")->fetchAll(PDO::FETCH_ASSOC);
            $lista_motoare = $conn->query("SELECT id_motorizare, nume_motor, putere_cp FROM Motorizari ORDER BY nume_motor")->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <h3><?= $action === 'add' ? 'Adaugă Versiune' : 'Editează Versiune' ?></h3>
            <form method="POST" class="admin-form">
                <input type="hidden" name="action" value="<?= $action ?>_versiune">
                <?php if ($action === 'edit'): ?><input type="hidden" name="id_versiune" value="<?= $id ?>"><?php endif; ?>
                
                <p><label>Model:</label>
                <select name="id_masina" required>
                    <?php foreach($lista_masini as $m_opt): ?>
                        <option value="<?= $m_opt['id_masina'] ?>" <?= $m_opt['id_masina'] == $v['id_masina'] ? 'selected' : '' ?>><?= htmlspecialchars($m_opt['nume_model']) ?></option>
                    <?php endforeach; ?>
                </select></p>

                <p><label>Motorizare:</label>
                <select name="id_motorizare" required>
                    <?php foreach($lista_motoare as $mo_opt): ?>
                        <option value="<?= $mo_opt['id_motorizare'] ?>" <?= $mo_opt['id_motorizare'] == $v['id_motorizare'] ? 'selected' : '' ?>><?= htmlspecialchars($mo_opt['nume_motor']) ?> (<?= $mo_opt['putere_cp'] ?> CP)</option>
                    <?php endforeach; ?>
                </select></p>

                <p><label>Nivel Echipare:</label><input type="text" name="nivel_echipare" value="<?= htmlspecialchars($v['nivel_echipare']) ?>" required></p>
                <p><label>Preț (€):</label><input type="number" step="0.01" name="pret_euro" value="<?= htmlspecialchars($v['pret_euro']) ?>" required></p>
                
                <button type="submit" class="btn">Salvează</button>
                <a href="admin-masini.php?tab=versiuni">Anulează</a>
            </form>
        <?php endif; ?>
    <?php endif; ?>
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
.tab-btn {
    padding: 10px 20px;
    text-decoration: none;
    color: #636e72;
    border-radius: 6px 6px 0 0;
    transition: all 0.2s;
    font-weight: bold;
}
.tab-btn:hover { background: #f1f2f6; }
.tab-btn.active {
    color: #00b894;
    border-bottom: 3px solid #00b894;
    background: #f1f2f6;
}
.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #00b894;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    border: none;
    cursor: pointer;
}
.btn:hover { background-color: #00a8ff; }
.admin-form p { margin-bottom: 15px; }
.admin-form label { display: block; font-weight: bold; margin-bottom: 5px; }
.admin-form input, .admin-form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.edit-link { color: #0984e3; text-decoration: none; font-weight: bold; }
.delete-btn { background: none; border: none; color: #d63031; cursor: pointer; font-weight: bold; }
</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
