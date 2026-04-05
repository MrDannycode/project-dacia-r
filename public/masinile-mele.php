<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Redirect dacă nu e logat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error   = '';

// ── DELETE config ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $del_id = (int)$_POST['delete_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM Comenzi WHERE id_comanda = :id AND id_utilizator = :uid");
        $stmt->execute([':id' => $del_id, ':uid' => $user_id]);
        $success = 'Configurația a fost ștearsă.';
    } catch (PDOException $e) {
        $error = 'Eroare la ștergere: ' . $e->getMessage();
    }
}

// ── Fetch configurările utilizatorului ──────────────────────────────────────
$stmt = $conn->prepare("
    SELECT
        c.id_comanda,
        c.culoare_exterior,
        c.pret_final_euro,
        c.status_comanda,
        c.data_crearii,
        ma.nume_model,
        ma.tip_caroserie,
        v.nivel_echipare,
        mo.nume_motor,
        mo.putere_cp,
        mo.tip_combustibil,
        mo.transmisie,
        mo.tractiune,
        mo.tip_electrificare
    FROM Comenzi c
    JOIN Versiuni v   ON v.id_versiune   = c.id_versiune
    JOIN Masini ma    ON ma.id_masina    = v.id_masina
    JOIN Motorizari mo ON mo.id_motorizare = v.id_motorizare
    WHERE c.id_utilizator = :uid
    ORDER BY c.data_crearii DESC
");
$stmt->execute([':uid' => $user_id]);
$comenzi = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<div class="mycar-page">

    <div class="mycar-header">
        <div class="mycar-header-left">
            <h1 class="page-title">Mașinile Mele</h1>
            <p class="page-subtitle">Configurările salvate în contul tău Dacia.</p>
        </div>
        <a href="config-model.php" class="cfg-btn-save mycar-new-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Configurație Nouă
        </a>
    </div>

    <?php if ($success): ?>
        <div class="cfg-alert cfg-alert--success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="cfg-alert cfg-alert--error">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($comenzi)): ?>
        <div class="mycar-empty">
            <div class="mycar-empty-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/>
                    <circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>
                </svg>
            </div>
            <h2>Nu ai configurații salvate</h2>
            <p>Creează-ți prima configurație Dacia acum și salvaz-o în contul tău.</p>
            <a href="config-model.php" class="cfg-btn-save">Configurează acum</a>
        </div>
    <?php else: ?>
        <div class="mycar-grid">
            <?php foreach ($comenzi as $c): ?>
                <div class="mycar-card">
                    <div class="mycar-card-header">
                        <div>
                            <span class="mycar-model"><?= htmlspecialchars($c['nume_model']) ?></span>
                            <span class="mycar-body"><?= htmlspecialchars($c['tip_caroserie']) ?></span>
                        </div>
                        <span class="mycar-status"><?= htmlspecialchars($c['status_comanda']) ?></span>
                    </div>

                    <div class="mycar-card-body">
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Nivel echipare</span>
                            <strong><?= htmlspecialchars($c['nivel_echipare']) ?></strong>
                        </div>
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Motorizare</span>
                            <strong><?= htmlspecialchars($c['nume_motor']) ?> · <?= $c['putere_cp'] ?> CP</strong>
                        </div>
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Combustibil</span>
                            <strong><?= htmlspecialchars($c['tip_combustibil']) ?></strong>
                        </div>
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Transmisie</span>
                            <strong><?= htmlspecialchars($c['transmisie']) ?></strong>
                        </div>
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Tracțiune</span>
                            <strong><?= htmlspecialchars($c['tractiune']) ?></strong>
                        </div>
                        <?php if ($c['tip_electrificare']): ?>
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Electrificare</span>
                            <strong class="mycar-eco"><?= htmlspecialchars($c['tip_electrificare']) ?></strong>
                        </div>
                        <?php endif; ?>
                        <div class="mycar-spec-row">
                            <span class="mycar-spec-label">Culoare</span>
                            <strong><?= htmlspecialchars($c['culoare_exterior']) ?></strong>
                        </div>
                    </div>

                    <div class="mycar-card-footer">
                        <div class="mycar-footer-left">
                            <span class="mycar-pret"><?= number_format($c['pret_final_euro'], 0, ',', '.') ?> €</span>
                            <span class="mycar-date"><?= date('d.m.Y', strtotime($c['data_crearii'])) ?></span>
                        </div>
                        <form method="POST" onsubmit="return confirm('Ștergi această configurație?')">
                            <input type="hidden" name="delete_id" value="<?= $c['id_comanda'] ?>">
                            <button type="submit" class="mycar-btn-delete" title="Șterge configurația">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                Șterge
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mycar-back">
        <a href="cont.php" class="btn-back-car">← Înapoi la cont</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
