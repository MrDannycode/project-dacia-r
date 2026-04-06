<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/includes/header.php';

// Fetch cheapest version for each car with engine details
$sql = "
    SELECT
        m.id_masina,
        m.nume_model,
        v.nivel_echipare,
        v.pret_euro,
        mo.nume_motor,
        mo.putere_cp,
        mo.tip_combustibil,
        mo.transmisie,
        mo.tractiune
    FROM Masini m
    JOIN Versiuni v ON v.id_masina = m.id_masina
    JOIN Motorizari mo ON mo.id_motorizare = v.id_motorizare
    WHERE v.pret_euro = (
        SELECT MIN(v2.pret_euro) FROM Versiuni v2 WHERE v2.id_masina = m.id_masina
    )
    ORDER BY m.id_masina
";

$stmt = $conn->query($sql);
$masini = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map model names to image files and detail pages
$modelMap = [
    'Sandero Stepway' => ['img' => 'sandero-mic.jpg', 'page' => 'car-sandero-stepway.php'],
    'Sandero' => ['img' => 'sandero-mic.jpg', 'page' => 'car-sandero.php'],
    'Logan' => ['img' => 'logan-mic.png', 'page' => 'car-logan.php'],
    'Duster' => ['img' => 'duster-mic.jpg', 'page' => 'car-duster.php'],
    'Bigster' => ['img' => 'bigster-mic.jpg', 'page' => 'car-bigster.php'],
    'Jogger' => ['img' => 'jogger-mic.jpg', 'page' => 'car-jogger.php'],
    'Spring' => ['img' => 'spring-micc.jpg', 'page' => 'car-spring.php'],
];
?>

<link rel="stylesheet" href="assets/css/nav-oferte-standard.css?v=<?= time() ?>">

<h1>Oferte Standard</h1>
<p>Vezi cele mai bune oferte – prețuri de la versiunea de bază:</p>

<div class="news-grid">
    <?php foreach ($masini as $masina): ?>
        <?php
        $model = $masina['nume_model'];
        $img = $modelMap[$model]['img'] ?? 'sandero-mic.jpg';
        $page = $modelMap[$model]['page'] ?? '#';
        $pret = number_format($masina['pret_euro'], 0, ',', '.');
        ?>
        <div class="news-card car-card">
            <h3 class="card-title-top"><?= htmlspecialchars($model) ?></h3>
            <img src="assets/images/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($model) ?>">

            <div class="car-spec-table">
                <div class="spec-row">
                    <span class="spec-label">Versiune</span>
                    <span class="spec-value"><?= htmlspecialchars($masina['nivel_echipare']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Motor</span>
                    <span class="spec-value"><?= htmlspecialchars($masina['nume_motor']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Putere</span>
                    <span class="spec-value"><?= htmlspecialchars($masina['putere_cp']) ?> CP</span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Combustibil</span>
                    <span class="spec-value"><?= htmlspecialchars($masina['tip_combustibil']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Transmisie</span>
                    <span class="spec-value"><?= htmlspecialchars($masina['transmisie']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Tractiune</span>
                    <span class="spec-value"><?= htmlspecialchars($masina['tractiune']) ?></span>
                </div>
                <div class="spec-row spec-price-row">
                    <span class="spec-label">Pret de la</span>
                    <span class="spec-value spec-price"><?= $pret ?> €</span>
                </div>
            </div>

            <div class="card-buttons">
                <a href="<?= htmlspecialchars($page) ?>" class="btn-account btn-small">Info</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>