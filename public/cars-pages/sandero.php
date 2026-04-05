<?php session_start(); ?>
<?php include __DIR__ . '/includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/sandero.css?v=<?= time() ?>">

<?php
$model_sandero = [
    "titlu" => "Noul Dacia Sandero",
    "subtitlu" => "Hatchback-ul modern, agil și accesibil",
    "descriere" => "Construit pe platforma modernă CMF-B, a treia generație Dacia Sandero se remarcă printr-un design dinamic, proporții echilibrate și o semnătură luminoasă distinctivă în formă de Y. Oferă un interior ergonomic, materiale de calitate superioară și tehnologii esențiale integrate inteligent, fiind partenerul ideal atât pentru traficul urban, cât și pentru escapadele de weekend.",
    "imagine" => "assets/images/sandero-info1.jfif",
    "specificatii" => [
        "Motorizare" => "SCe 65 / TCe 90 / ECO-G 100",
        "Volum Portbagaj" => "328 Litri",
        "Multimedia" => "Media Control / Display 8\""
    ],
    "dotari" => [
        "Faruri Eco-LED cu semnătură luminoasă Y",
        "Sistem de frânare de urgență automat (AEBS)",
        "Sistem Media Control cu suport pentru smartphone",
        "Climatizare automată",
        "Senzori de parcare spate și cameră video",
        "Senzor de unghi mort",
        "Card mâini libere (Keyless Entry)",
        "Ștergătoare cu senzor de ploaie",
        "Cruise Control (Pilot automat) și limitator",
        "Jante din aliaj de 16\""
    ]
];
?>

<div class="sandero-content-wrapper">
    <div class="sandero-hero">
        <h1><?php echo htmlspecialchars($model_sandero['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_sandero['subtitlu']); ?></p>
    </div>

    <div class="sandero-card">
        <img class="sandero-image" src="<?php echo htmlspecialchars($model_sandero['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_sandero['titlu']); ?>">

        <div class="sandero-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_sandero['descriere']); ?></p>

            <div class="sandero-specs">
                <?php foreach ($model_sandero['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sandero-features">
                <h3>Dotări și Echipamente</h3>
                <ul class="features-list">
                    <?php foreach ($model_sandero['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <img class="sandero-image" src="assets/images/sandero-info2.jfif" alt="Dacia Sandero - detalii suplimentare">
    </div>


    <div class="sandero-back-btn">
        <a href="autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>