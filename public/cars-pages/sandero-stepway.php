<?php session_start(); ?>
<?php include __DIR__ . '/includes/header.php'; ?>


<link rel="stylesheet" href="assets/css/sandero-stepway.css?v=<?= time() ?>">


<?php
$model_stepway = [
    "titlu" => "Noul Dacia Sandero Stepway",
    "subtitlu" => "Crossover-ul urban gata de aventură",
    "descriere" => "Cu o gardă la sol înălțată, bare de pavilion modulare și un design impunător de crossover, noul Dacia Sandero Stepway este pregătit pentru orice drum. Combină agilitatea unui hatchback cu robustețea unui SUV, oferind un interior confortabil, tehnologie modernă și un spirit aventurier inconfundabil.",
    "imagine" => "assets/images/san-step1.jfif",
    "specificatii" => [
        "Motorizare" => "TCe 90 / ECO-G 100 / TCe 110",
        "Gardă la sol" => "201 mm",
        "Multimedia" => "Media Display 8\" / Media Nav"
    ],
    "dotari" => [
        "Bare de pavilion longitudinale modulare",
        "Gardă la sol supraînălțată și protecții laterale",
        "Faruri Eco-LED cu semnătură luminoasă Y",
        "Sistem de frânare de urgență automat (AEBS)",
        "Climatizare automată",
        "Senzori de parcare și cameră video pentru marșarier",
        "Senzor de unghi mort",
        "Card mâini libere (Keyless Entry)",
        "Jante din aliaj 16\" Mahalia",
        "Frână de parcare asistată electric"
    ]
];
?>

<div class="stepway-content-wrapper">
    <div class="stepway-hero">
        <h1><?php echo htmlspecialchars($model_stepway['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_stepway['subtitlu']); ?></p>
    </div>

    <div class="stepway-card">
        <img class="stepway-image" src="<?php echo htmlspecialchars($model_stepway['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_stepway['titlu']); ?>">

        <div class="stepway-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_stepway['descriere']); ?></p>

            <div class="stepway-specs">
                <?php foreach ($model_stepway['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="stepway-features">
                <h3>Dotări și Echipamente</h3>
                <ul class="features-list">
                    <?php foreach ($model_stepway['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <img class="stepway-image" src="assets/images/san-step2.jfif"
            alt="Dacia Sandero Stepway - detalii suplimentare">
    </div>


    <div class="stepway-back-btn">
        <a href="autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>