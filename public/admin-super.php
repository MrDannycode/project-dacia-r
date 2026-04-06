<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Only super_admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: index.php");
    exit();
}

$error   = '';
$success = '';

// ── Handle POST actions ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';

    // Change a user's role
    if ($act === 'change_role') {
        $uid  = (int)($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? '';
        $allowed = ['customer', 'news_admin', 'car_admin', 'user_admin', 'super_admin'];

        if ($uid && in_array($role, $allowed)) {
            // Prevent the current super admin from demoting themselves
            if ($uid === (int)$_SESSION['user_id'] && $role !== 'super_admin') {
                $error = 'Nu îți poți modifica propriul rol!';
            } else {
                $stmt = $conn->prepare("UPDATE utilizatori SET role = :role WHERE id_utilizator = :id");
                $stmt->execute([':role' => $role, ':id' => $uid]);
                $success = 'Rolul utilizatorului a fost actualizat cu succes!';
            }
        } else {
            $error = 'Date invalide pentru schimbarea rolului.';
        }
    }

    // Delete a user
    if ($act === 'delete_user') {
        $uid = (int)($_POST['user_id'] ?? 0);
        if ($uid && $uid !== (int)$_SESSION['user_id']) {
            // Delete the user's commands first (FK constraint)
            $conn->prepare("DELETE FROM Comenzi WHERE id_utilizator = :id")->execute([':id' => $uid]);
            $conn->prepare("DELETE FROM utilizatori WHERE id_utilizator = :id")->execute([':id' => $uid]);
            $success = 'Utilizatorul a fost șters!';
        } elseif ($uid === (int)$_SESSION['user_id']) {
            $error = 'Nu poți șterge propriul cont!';
        }
    }
}

// ── Fetch all users ──────────────────────────────────────────────────────────
$search  = trim($_GET['search'] ?? '');
$filter  = $_GET['filter'] ?? 'all';
$allowed_roles = ['customer', 'news_admin', 'car_admin', 'user_admin', 'super_admin'];

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(first_name ILIKE :s OR last_name ILIKE :s OR email ILIKE :s)";
    $params[':s'] = '%' . $search . '%';
}
if ($filter !== 'all' && in_array($filter, $allowed_roles)) {
    $where[] = "role = :role";
    $params[':role'] = $filter;
}

$sql = "SELECT id_utilizator, first_name, last_name, email, role, created_at
        FROM utilizatori";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Role counts for stats ────────────────────────────────────────────────────
$countStmt = $conn->query("SELECT role, COUNT(*) as cnt FROM utilizatori GROUP BY role");
$counts = [];
foreach ($countStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $counts[$row['role']] = $row['cnt'];
}
$total = array_sum($counts);

require_once __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="assets/css/admin-super.css?v=<?= time() ?>">

<div class="sa-wrapper">

    <!-- ── Page Header ─────────────────────────────── -->
    <div class="sa-page-header">
        <div class="sa-page-header-left">
            <span class="sa-badge">Super Admin</span>
            <h2>Panou de Administrare</h2>
            <p>Gestionează utilizatorii și rolurile platformei Dacia.</p>
        </div>
        <div class="sa-page-header-right">
            <img src="assets/images/logo.jpg" alt="Dacia Logo" class="sa-logo">
        </div>
    </div>

    <!-- ── Alerts ──────────────────────────────────── -->
    <?php if ($error): ?>
        <div class="sa-alert sa-alert-error">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="sa-alert sa-alert-success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- ── Stats Cards ─────────────────────────────── -->
    <div class="sa-stats">
        <div class="sa-stat-card">
            <div class="sa-stat-icon">👥</div>
            <div class="sa-stat-body">
                <span class="sa-stat-number"><?= $total ?></span>
                <span class="sa-stat-label">Total Utilizatori</span>
            </div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon">🛒</div>
            <div class="sa-stat-body">
                <span class="sa-stat-number"><?= $counts['customer'] ?? 0 ?></span>
                <span class="sa-stat-label">Clienți</span>
            </div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon">📰</div>
            <div class="sa-stat-body">
                <span class="sa-stat-number"><?= $counts['news_admin'] ?? 0 ?></span>
                <span class="sa-stat-label">Admin Știri</span>
            </div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon">🚗</div>
            <div class="sa-stat-body">
                <span class="sa-stat-number"><?= $counts['car_admin'] ?? 0 ?></span>
                <span class="sa-stat-label">Admin Mașini</span>
            </div>
        </div>
        <div class="sa-stat-card">
            <div class="sa-stat-icon">👨‍💼</div>
            <div class="sa-stat-body">
                <span class="sa-stat-number"><?= $counts['user_admin'] ?? 0 ?></span>
                <span class="sa-stat-label">Admin Utilizatori</span>
            </div>
        </div>
        <div class="sa-stat-card sa-stat-card--super">
            <div class="sa-stat-icon">⭐</div>
            <div class="sa-stat-body">
                <span class="sa-stat-number"><?= $counts['super_admin'] ?? 0 ?></span>
                <span class="sa-stat-label">Super Admin</span>
            </div>
        </div>
    </div>

    <!-- ── Search & Filter Bar ─────────────────────── -->
    <div class="sa-toolbar">
        <form method="GET" class="sa-search-form">
            <input type="text" name="search" placeholder="Caută după nume sau email…" value="<?= htmlspecialchars($search) ?>" class="sa-search-input">
            <select name="filter" class="sa-filter-select">
                <option value="all"     <?= $filter === 'all'        ? 'selected' : '' ?>>Toate rolurile</option>
                <option value="customer"       <?= $filter === 'customer'       ? 'selected' : '' ?>>Clienți</option>
                <option value="news_admin"     <?= $filter === 'news_admin'     ? 'selected' : '' ?>>Admin Știri</option>
                <option value="car_admin"      <?= $filter === 'car_admin'      ? 'selected' : '' ?>>Admin Mașini</option>
                <option value="user_admin"     <?= $filter === 'user_admin'     ? 'selected' : '' ?>>Admin Utilizatori</option>
                <option value="super_admin"    <?= $filter === 'super_admin'    ? 'selected' : '' ?>>Super Admin</option>
            </select>
            <button type="submit" class="sa-btn sa-btn-primary">Filtrează</button>
            <?php if ($search || $filter !== 'all'): ?>
                <a href="admin-super.php" class="sa-btn sa-btn-ghost">Resetează</a>
            <?php endif; ?>
        </form>
        <span class="sa-result-count"><?= count($users) ?> utilizator<?= count($users) !== 1 ? 'i' : '' ?> găsit<?= count($users) !== 1 ? 'i' : '' ?></span>
    </div>

    <!-- ── Users Table ─────────────────────────────── -->
    <div class="sa-table-wrapper">
        <?php if (empty($users)): ?>
            <div class="sa-empty">Nu există utilizatori care să corespundă criteriilor de căutare.</div>
        <?php else: ?>
        <table class="sa-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilizator</th>
                    <th>Email</th>
                    <th>Rol Curent</th>
                    <th>Cont Creat</th>
                    <th>Schimbă Rol</th>
                    <th>Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr class="sa-row <?= $u['id_utilizator'] == $_SESSION['user_id'] ? 'sa-row--self' : '' ?>">
                    <td class="sa-cell-id">#<?= $u['id_utilizator'] ?></td>
                    <td class="sa-cell-name">
                        <div class="sa-avatar" data-role="<?= $u['role'] ?>">
                            <?= strtoupper(mb_substr($u['first_name'], 0, 1) . mb_substr($u['last_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <strong><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                            <?php if ($u['id_utilizator'] == $_SESSION['user_id']): ?>
                                <span class="sa-you-badge">Tu</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="sa-role-pill sa-role-<?= $u['role'] ?>">
                            <?= match($u['role']) {
                                'customer'    => '🛒 Client',
                                'news_admin'  => '📰 Admin Știri',
                                'car_admin'   => '🚗 Admin Mașini',
                                'user_admin'  => '👨‍💼 Admin Utilizatori',
                                'super_admin' => '⭐ Super Admin',
                                default       => $u['role']
                            } ?>
                        </span>
                    </td>
                    <td class="sa-cell-date"><?= date('d.m.Y H:i', strtotime($u['created_at'])) ?></td>

                    <!-- Change Role -->
                    <td>
                        <?php if ($u['id_utilizator'] != $_SESSION['user_id']): ?>
                        <form method="POST" class="sa-inline-form">
                            <input type="hidden" name="action" value="change_role">
                            <input type="hidden" name="user_id" value="<?= $u['id_utilizator'] ?>">
                            <select name="role" class="sa-role-select">
                                <option value="customer"    <?= $u['role'] === 'customer'    ? 'selected' : '' ?>>Client</option>
                                <option value="news_admin"  <?= $u['role'] === 'news_admin'  ? 'selected' : '' ?>>Admin Știri</option>
                                <option value="car_admin"   <?= $u['role'] === 'car_admin'   ? 'selected' : '' ?>>Admin Mașini</option>
                                <option value="user_admin"  <?= $u['role'] === 'user_admin'  ? 'selected' : '' ?>>Admin Utilizatori</option>
                                <option value="super_admin" <?= $u['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            </select>
                            <button type="submit" class="sa-btn sa-btn-sm sa-btn-primary">Salvează</button>
                        </form>
                        <?php else: ?>
                            <em class="sa-muted">Propriul tău cont</em>
                        <?php endif; ?>
                    </td>

                    <!-- Delete -->
                    <td>
                        <?php if ($u['id_utilizator'] != $_SESSION['user_id']): ?>
                        <form method="POST" class="sa-inline-form" onsubmit="return confirm('Sigur ștergi utilizatorul <?= htmlspecialchars(addslashes($u['first_name'] . ' ' . $u['last_name'])) ?>? Această acțiune este ireversibilă!');">
                            <input type="hidden" name="action" value="delete_user">
                            <input type="hidden" name="user_id" value="<?= $u['id_utilizator'] ?>">
                            <button type="submit" class="sa-btn sa-btn-sm sa-btn-danger">🗑 Șterge</button>
                        </form>
                        <?php else: ?>
                            <span class="sa-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
