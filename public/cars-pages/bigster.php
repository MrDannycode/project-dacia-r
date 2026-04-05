<?php session_start(); ?>
<?php include __DIR__ . '/includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/bigster.css?v=<?= time() ?>">

<?php
$model_bigster = [
    "titlu" => "Noul Dacia Bigster",
    "subtitlu" => "Cel mai mare și tehnologizat SUV din istoria mărcii",
    "descriere" => "Cu o lungime de 4,57 metri, Dacia Bigster marchează intrarea spectaculoasă în segmentul C-SUV. Păstrând designul robust și vertical inspirat de Duster, fratele mai mare plusează printr-un spațiu interior impresionant, un portbagaj record în clasa sa și materiale prietenoase cu mediul. Totodată, aduce în premieră pentru Dacia noul motor puternic HYBRID 155 și un nivel de confort nemaivăzut pe modelele mărcii.",
    "imagine" => "assets/images/bigster-info1.jfif",
    "specificatii" => [
        "Motorizare" => "HYBRID 155 / mild-hybrid 140 / ECO-G 140",
        "Volum portbagaj" => "Până la 702 Litri (VDA)",
        "Tracțiune" => "Disponibil 4x2 și 4x4"
    ],
    "dotari" => [
        "Plafon panoramic din sticlă cu deschidere electrică",
        "Hayon cu acționare electrică automatizată",
        "Instrumentar de bord digital de 10 inch si Media Display 10 inch",
        "Scaun șofer cu reglaj electric și suport lombar",
        "Sistem multimedia cu 6 difuzoare și Arkamys 3D Sound",
        "Consolă centrală înaltă cu cotieră și compartiment frigorific",
        "Adaptive Cruise Control (Pilot automat adaptiv)",
        "Scaune față, volan și parbriz încălzite",
        "Aer condiționat automat pe două zone",
        "Banchetă spate fracționabilă 40/20/40 cu funcție Easy Fold"
    ]
];
?>

<div class="bigster-content-wrapper">
    <div class="bigster-hero">
        <h1><?php echo htmlspecialchars($model_bigster['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_bigster['subtitlu']); ?></p>
    </div>

    <div class="bigster-card">
        <img class="bigster-image" src="<?php echo htmlspecialchars($model_bigster['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_bigster['titlu']); ?>">

        <div class="bigster-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_bigster['descriere']); ?></p>

            <div class="bigster-specs">
                <?php foreach ($model_bigster['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="bigster-features">
                <h3>Dotări Exclusiviste</h3>
                <ul class="features-list">
                    <?php foreach ($model_bigster['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <img class="bigster-image" src="assets/images/bigster-info2.jfif" alt="Dacia Bigster - detalii suplimentare">
    </div>

    <div class="bigster-back-btn">
        <a href="autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>