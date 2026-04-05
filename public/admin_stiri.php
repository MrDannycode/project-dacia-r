<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], ['news_admin', 'super_admin'])) {
    header("Location: index.php");
    exit();
}

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    
    if ($act === 'add' || $act === 'edit') {
        $titlu = $_POST['titlu'] ?? '';
        $descriere = $_POST['descriere'] ?? '';
        $continut = $_POST['continut'] ?? '';
        $imagine = $_POST['imagine'] ?? 'assets/images/news-lansare.jpg';
        $alt_imagine = $_POST['alt_imagine'] ?? '';
        
        if (empty($titlu) || empty($descriere) || empty($continut)) {
            $error = 'Titlul, descrierea și conținutul sunt obligatorii!';
        } else {
            if ($act === 'add') {
                $sql = "INSERT INTO Stiri (titlu, descriere, continut, imagine, alt_imagine) VALUES (:titlu, :descriere, :continut, :imagine, :alt_imagine)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':titlu' => $titlu,
                    ':descriere' => $descriere,
                    ':continut' => $continut,
                    ':imagine' => $imagine,
                    ':alt_imagine' => $alt_imagine
                ]);
                $success = 'Știrea a fost adăugată cu succes!';
                $action = 'list';
            } elseif ($act === 'edit') {
                $id = $_POST['id_stire'] ?? 0;
                $sql = "UPDATE Stiri SET titlu = :titlu, descriere = :descriere, continut = :continut, imagine = :imagine, alt_imagine = :alt_imagine WHERE id_stire = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':titlu' => $titlu,
                    ':descriere' => $descriere,
                    ':continut' => $continut,
                    ':imagine' => $imagine,
                    ':alt_imagine' => $alt_imagine,
                    ':id' => $id
                ]);
                $success = 'Știrea a fost actualizată cu succes!';
                $action = 'list';
            }
        }
    } elseif ($act === 'delete') {
         $id = $_POST['id_stire'] ?? 0;
         $sql = "DELETE FROM Stiri WHERE id_stire = :id";
         $stmt = $conn->prepare($sql);
         $stmt->execute([':id' => $id]);
         $success = 'Știrea a fost ștearsă!';
         $action = 'list';
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="content">
    <h2>Administrare Știri</h2>
    
    <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <?php if ($action === 'list'): ?>
        <a href="admin_stiri.php?action=add" class="btn">Adaugă Știre Nouă</a>
        <br><br>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Dată Publicare</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM Stiri ORDER BY data_publicarii DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_stire'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['titlu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_publicarii']) . "</td>";
                    echo "<td>
                            <a href='admin_stiri.php?action=edit&id=" . $row['id_stire'] . "'>Edit</a> | 
                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Sigur ștergi această știre?\");'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='id_stire' value='" . $row['id_stire'] . "'>
                                <button type='submit' style='background:none; border:none; color:red; cursor:pointer;'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php elseif ($action === 'add' || $action === 'edit'): 
        $stire = ['titlu' => '', 'descriere' => '', 'continut' => '', 'imagine' => '', 'alt_imagine' => ''];
        $id = 0;
        if ($action === 'edit') {
            $id = $_GET['id'] ?? 0;
            $stmt = $conn->prepare("SELECT * FROM Stiri WHERE id_stire = :id");
            $stmt->execute([':id' => $id]);
            $stire = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$stire) {
                echo "<p>Știrea nu a fost găsită!</p>";
                $action = 'list';
            }
        }
        if ($action !== 'list'):
    ?>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $action ?>">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="id_stire" value="<?= $id ?>">
            <?php endif; ?>
            
            <p>
                <label>Titlu:</label><br>
                <input type="text" name="titlu" value="<?= htmlspecialchars($stire['titlu']) ?>" style="width: 100%; padding: 8px;" required>
            </p>
            <p>
                <label>Scurtă Descriere (pentru lista de știri):</label><br>
                <textarea name="descriere" style="width: 100%; height: 60px; padding: 8px;" required><?= htmlspecialchars($stire['descriere']) ?></textarea>
            </p>
            <p>
                <label>Conținut HTML (detalii pagină știre):</label><br>
                <textarea name="continut" style="width: 100%; height: 150px; padding: 8px;" required><?= htmlspecialchars($stire['continut']) ?></textarea>
            </p>
            <p>
                <label>Calea Imaginii (ex: assets/images/news-lansare.jpg):</label><br>
                <input type="text" name="imagine" value="<?= htmlspecialchars($stire['imagine']) ?>" style="width: 100%; padding: 8px;">
            </p>
            <p>
                <label>Alt text pentru imagine:</label><br>
                <input type="text" name="alt_imagine" value="<?= htmlspecialchars($stire['alt_imagine']) ?>" style="width: 100%; padding: 8px;">
            </p>
            <button type="submit" class="btn" style="border:none; cursor:pointer; font-size:16px; margin-top:10px;">
                <?= $action === 'add' ? 'Adaugă Știrea' : 'Salvează Modificări' ?>
            </button>
            <a href="admin_stiri.php" style="margin-left: 15px;">Renunță</a>
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
    padding: 0.75rem;
    text-align: left;
}
.btn {
    display: inline-block;
    padding: 10px 15px;
    background-color: #f75d34;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
}
.btn:hover {
    background-color: #e04c2b;
}
</style>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
