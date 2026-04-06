<?php session_start(); ?>
<?php 
$is_subdir = true;
include __DIR__ . '/../includes/header.php'; 
?>

<link rel="stylesheet" href="../assets/css/logan.css?v=<?= time() ?>">

<?php
$model_logan = [
    "titlu" => "Noul Dacia Logan",
    "subtitlu" => "Evoluția sedanului tău preferat",
    "descriere" => "Construită pe noua platformă CMF-B, ultima generație de Dacia Logan oferă un design complet redesenat, faruri LED cu semnătura luminoasă în formă de Y, sistem multimedia avansat și sisteme de siguranță moderne, precum frânarea automată de urgență. Păstrează atributele care au consacrat-o: un habitaclu spațios și un portbagaj de clasă superioară.",
    "imagine" => "../assets/images/info/logan-info1.jfif",
    "specificatii" => [
        "Motorizare" => "TCe 90 / ECO-G 100",
        "Volum Portbagaj" => "528 Litri",
        "Multimedia" => "Media Display 8\""
    ],
    "dotari" => [
        "Faruri Eco-LED (fază scurtă și lumini de zi)",
        "Sistem de frânare de urgență automat (AEBS)",
        "Card mâini libere (Keyless Entry & Go)",
        "Climatizare automată cu afișaj digital",
        "Senzori de parcare față/spate și cameră marșarier",
        "Senzor de unghi mort",
        "Frână de parcare asistată electric",
        "Ștergătoare cu senzor de ploaie",
        "Cruise Control (Pilot automat) și limitator de viteză",
        "Conectivitate wireless Apple CarPlay și Android Auto"
    ]
];
?>

<div class="logan-content-wrapper">
    <div class="logan-hero">
        <h1><?php echo htmlspecialchars($model_logan['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_logan['subtitlu']); ?></p>
    </div>

    <div class="logan-card">
        <img class="logan-image" src="<?php echo htmlspecialchars($model_logan['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_logan['titlu']); ?>">

        <div class="logan-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_logan['descriere']); ?></p>

            <div class="logan-specs">
                <?php foreach ($model_logan['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="logan-features">
                <h3>Dotări și Echipamente</h3>
                <ul class="features-list">
                    <?php foreach ($model_logan['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <img class="logan-image" src="../assets/images/info/logan-info2.jfif" alt="Dacia Logan - detalii suplimentare">
    </div>

    <div class="logan-back-btn">
        <a href="../nav-autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>