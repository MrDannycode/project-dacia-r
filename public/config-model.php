<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Redirect dacă nu e logat
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$success = '';
$error = '';

// ── 1. POST: salvează configurația ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_versiune = (int) ($_POST['id_versiune'] ?? 0);
    $culoare = trim($_POST['culoare'] ?? 'Alb');
    $pret_final = (float) ($_POST['pret_final'] ?? 0);

    if ($id_versiune > 0 && $pret_final > 0) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO Comenzi (id_utilizator, id_versiune, culoare_exterior, pret_final_euro, status_comanda)
                VALUES (:uid, :vid, :culoare, :pret, 'Configurare Salvată')
            ");
            $stmt->execute([
                ':uid' => $_SESSION['user_id'],
                ':vid' => $id_versiune,
                ':culoare' => $culoare,
                ':pret' => $pret_final,
            ]);
            $success = 'Configurația a fost salvată cu succes!';
        } catch (PDOException $e) {
            $error = 'Eroare la salvare: ' . $e->getMessage();
        }
    } else {
        $error = 'Te rugăm să selectezi un model și o versiune valide.';
    }
}

// ── 2. Încarcă mașinile disponibile ─────────────────────────────────────────
$masini = $conn->query("SELECT * FROM Masini ORDER BY nume_model")->fetchAll(PDO::FETCH_ASSOC);

// ── 3. Încarcă TOATE versiunile + detalii motorizare (JOIN) ─────────────────
$versiuni_raw = $conn->query("
    SELECT
        v.id_versiune,
        v.id_masina,
        v.nivel_echipare,
        v.pret_euro,
        m.nume_motor,
        m.tip_combustibil,
        m.putere_cp,
        m.transmisie,
        m.tractiune,
        m.tip_electrificare
    FROM Versiuni v
    JOIN Motorizari m ON m.id_motorizare = v.id_motorizare
    ORDER BY v.id_masina, v.pret_euro
")->fetchAll(PDO::FETCH_ASSOC);

// Grupăm versiunile per masina pentru JS
$versiuni_by_masina = [];
foreach ($versiuni_raw as $v) {
    $versiuni_by_masina[$v['id_masina']][] = $v;
}

// Culorile disponibile
$culori = [
    'Alb Glacier' => '#f0f0f0',
    'Negru Nacré' => '#1a1a1a',
    'Gri Schist' => '#757575',
    'Gri Comète' => '#a0a0a8',
    'Albastru Iron' => '#2a4a7f',
    'Verde Cedar' => '#4a7a5a',
    'Roșu Flame' => '#c0392b',
    'Bej Dunelor' => '#c8a882',
];

include __DIR__ . '/includes/header.php';
?>

<div class="configurator-page">

    <div class="configurator-header">
        <h1 class="page-title">Configurează-ți Dacia</h1>
        <p class="page-subtitle">Alege modelul, versiunea, motorizarea și culoarea preferată, apoi salvează configurația
            în contul tău.</p>
    </div>

    <?php if ($success): ?>
        <div class="cfg-alert cfg-alert--success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <?= htmlspecialchars($success) ?>
            <a href="account-cars.php" class="cfg-alert__link">→ Vezi configurările mele</a>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="cfg-alert cfg-alert--error">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="configurator-form" id="cfg-form">

        <!-- STEP 1: Model -->
        <div class="cfg-section">
            <div class="cfg-step-badge">01</div>
            <h2 class="cfg-section-title">Alege modelul</h2>
            <div class="cfg-model-grid" id="model-grid">
                <?php foreach ($masini as $m): ?>
                    <label class="cfg-model-card" id="model-card-<?= $m['id_masina'] ?>">
                        <input type="radio" name="id_masina_js" value="<?= $m['id_masina'] ?>" class="cfg-model-radio"
                            required>
                        <div class="cfg-model-inner">
                            <span class="cfg-model-name"><?= htmlspecialchars($m['nume_model']) ?></span>
                            <span class="cfg-model-body"><?= htmlspecialchars($m['tip_caroserie']) ?> ·
                                <?= $m['numar_locuri'] ?> locuri</span>
                        </div>
                        <div class="cfg-model-check">✓</div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- STEP 2: Versiune / Motorizare -->
        <div class="cfg-section" id="section-versiune" style="display:none;">
            <div class="cfg-step-badge">02</div>
            <h2 class="cfg-section-title">Alege versiunea &amp; motorizarea</h2>
            <div class="cfg-versions-list" id="versions-list"></div>
            <input type="hidden" name="id_versiune" id="input-versiune" value="">
            <input type="hidden" name="pret_final" id="input-pret" value="">
        </div>

        <!-- STEP 3: Culoare -->
        <div class="cfg-section" id="section-culoare" style="display:none;">
            <div class="cfg-step-badge">03</div>
            <h2 class="cfg-section-title">Alege culoarea</h2>
            <div class="cfg-color-grid">
                <?php foreach ($culori as $nume => $hex): ?>
                    <label class="cfg-color-swatch" title="<?= htmlspecialchars($nume) ?>">
                        <input type="radio" name="culoare" value="<?= htmlspecialchars($nume) ?>" class="cfg-color-radio"
                            <?= $nume === 'Alb Glacier' ? 'checked' : '' ?>>
                        <span class="cfg-color-dot" style="background:<?= $hex ?>;"></span>
                        <span class="cfg-color-label"><?= htmlspecialchars($nume) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- STEP 4: Sumar & Salvare -->
        <div class="cfg-section cfg-summary-section" id="section-summary" style="display:none;">
            <div class="cfg-step-badge">04</div>
            <h2 class="cfg-section-title">Rezumat &amp; Salvare</h2>
            <div class="cfg-summary-box" id="summary-box">
                <div class="cfg-summary-row"><span>Model</span><strong id="sum-model">—</strong></div>
                <div class="cfg-summary-row"><span>Versiune</span><strong id="sum-versiune">—</strong></div>
                <div class="cfg-summary-row"><span>Motorizare</span><strong id="sum-motor">—</strong></div>
                <div class="cfg-summary-row"><span>Culoare</span><strong id="sum-culoare">—</strong></div>
                <div class="cfg-summary-row cfg-summary-total"><span>Preț</span><strong id="sum-pret">—</strong></div>
            </div>
            <button type="submit" class="cfg-btn-save" id="btn-save">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />
                    <polyline points="7 3 7 8 15 8" />
                </svg>
                Salvează Configurația
            </button>
        </div>

    </form>
</div>

<!-- Datele versiunilor ca JSON pentru JavaScript -->
<script>
    const VERSIUNI = <?= json_encode($versiuni_by_masina, JSON_UNESCAPED_UNICODE) ?>;
    const MASINI = <?= json_encode(array_column($masini, null, 'id_masina'), JSON_UNESCAPED_UNICODE) ?>;

    // ── UI logic ──────────────────────────────────────────────────────────────────
    let selectedModel = null;
    let selectedVersiune = null;

    // Selectare model
    document.querySelectorAll('.cfg-model-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            selectedModel = parseInt(this.value);
            selectedVersiune = null;
            document.getElementById('input-versiune').value = '';
            document.getElementById('input-pret').value = '';

            // Highlight card
            document.querySelectorAll('.cfg-model-card').forEach(c => c.classList.remove('is-selected'));
            this.closest('.cfg-model-card').classList.add('is-selected');

            renderVersions(selectedModel);
            document.getElementById('section-versiune').style.display = '';
            document.getElementById('section-culoare').style.display = 'none';
            document.getElementById('section-summary').style.display = 'none';
            updateSummaryModel();
        });
    });

    function renderVersions(id_masina) {
        const list = document.getElementById('versions-list');
        list.innerHTML = '';
        const vers = VERSIUNI[id_masina] || [];
        if (!vers.length) {
            list.innerHTML = '<p style="color:var(--color-text-muted)">Nu există versiuni disponibile pentru acest model.</p>';
            return;
        }
        vers.forEach(v => {
            const card = document.createElement('label');
            card.className = 'cfg-version-card';
            card.dataset.versiune = JSON.stringify(v);
            card.innerHTML = `
            <input type="radio" name="id_versiune_ui" value="${v.id_versiune}" class="cfg-version-radio">
            <div class="cfg-version-inner">
                <div class="cfg-version-top">
                    <span class="cfg-version-nivel">${v.nivel_echipare}</span>
                    <span class="cfg-version-pret">${Number(v.pret_euro).toLocaleString('ro-RO')} €</span>
                </div>
                <div class="cfg-version-specs">
                    <span class="cfg-spec-tag">${v.nume_motor}</span>
                    <span class="cfg-spec-tag">${v.putere_cp} CP</span>
                    <span class="cfg-spec-tag">${v.transmisie}</span>
                    <span class="cfg-spec-tag">${v.tractiune}</span>
                    ${v.tip_electrificare ? `<span class="cfg-spec-tag cfg-spec-tag--eco">${v.tip_electrificare}</span>` : ''}
                </div>
            </div>
            <div class="cfg-model-check">✓</div>
        `;
            list.appendChild(card);

            card.querySelector('.cfg-version-radio').addEventListener('change', function () {
                selectedVersiune = v;
                document.getElementById('input-versiune').value = v.id_versiune;
                document.getElementById('input-pret').value = v.pret_euro;

                document.querySelectorAll('.cfg-version-card').forEach(c => c.classList.remove('is-selected'));
                card.classList.add('is-selected');

                document.getElementById('section-culoare').style.display = '';
                document.getElementById('section-summary').style.display = '';
                updateSummary();
            });
        });
    }

    // Culoare
    document.querySelectorAll('.cfg-color-radio').forEach(r => {
        r.addEventListener('change', updateSummary);
    });

    function updateSummaryModel() {
        if (selectedModel && MASINI[selectedModel]) {
            document.getElementById('sum-model').textContent = MASINI[selectedModel].nume_model;
        }
    }

    function updateSummary() {
        updateSummaryModel();
        if (selectedVersiune) {
            document.getElementById('sum-versiune').textContent = selectedVersiune.nivel_echipare;
            document.getElementById('sum-motor').textContent =
                selectedVersiune.nume_motor + ' · ' + selectedVersiune.putere_cp + ' CP';
            document.getElementById('sum-pret').textContent =
                Number(selectedVersiune.pret_euro).toLocaleString('ro-RO') + ' €';
        }
        const culoare = document.querySelector('.cfg-color-radio:checked');
        if (culoare) {
            document.getElementById('sum-culoare').textContent = culoare.value;
        }
    }

    // Re-update culoare la schimbare
    document.querySelectorAll('.cfg-color-radio').forEach(r => {
        r.addEventListener('change', function () {
            document.querySelectorAll('.cfg-color-swatch').forEach(s => s.classList.remove('is-selected'));
            this.closest('.cfg-color-swatch').classList.add('is-selected');
            updateSummary();
        });
    });

    // Init culoare default
    const defaultColor = document.querySelector('.cfg-color-radio:checked');
    if (defaultColor) defaultColor.closest('.cfg-color-swatch').classList.add('is-selected');
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>