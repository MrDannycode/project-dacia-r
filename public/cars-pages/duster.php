<?php session_start(); ?>
<?php 
$is_subdir = true;
include __DIR__ . '/../includes/header.php'; 
?>

<link rel="stylesheet" href="../assets/css/duster.css?v=<?= time() ?>">

<?php
$model_duster = [
    "titlu" => "Noul Dacia Duster",
    "subtitlu" => "SUV-ul iconic, mai robust și mai tehnologizat ca niciodată",
    "descriere" => "A treia generație Dacia Duster își păstrează spiritul aventurier, dar vine cu un design complet nou, mai vertical și mai impunător. Construit pe platforma CMF-B, oferă pentru prima dată motorizări electrificate (HYBRID și mild-hybrid), materiale inovatoare și sustenabile precum Starkle®, și capacități off-road la un nivel superior. Este partenerul ideal pentru orice teren, combinând durabilitatea legendară cu tehnologia modernă.",
    "imagine" => "../assets/images/info/duster-info1.jfif",
    "specificatii" => [
        "Motorizare" => "HYBRID 140 / TCe 130 48V / ECO-G 100",
        "Tracțiune" => "Disponibil 4x2 sau 4x4",
        "Gardă la sol" => "Până la 217 mm"
    ],
    "dotari" => [
        "Sistem 4x4 Terrain Control cu 5 moduri de condus",
        "Ecran central tactil de 10.1\" (Media Display / Nav Live)",
        "Tablou de bord digital de 7\"",
        "Materiale sustenabile Starkle® (fără crom sau piele animală)",
        "Sistem inteligent de prindere a accesoriilor YouClip",
        "Asistență la coborârea în pantă (HDC)",
        "Sistem de camere 360° (Multiview Camera)",
        "Recunoașterea semnelor de circulație și frânare de urgență",
        "Climatizare automată și scaune încălzite",
        "Jante din aliaj ușor de 17\" sau 18\""
    ]
];
?>

<div class="duster-content-wrapper">
    <div class="duster-hero">
        <h1><?php echo htmlspecialchars($model_duster['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_duster['subtitlu']); ?></p>
    </div>

    <div class="duster-card">
        <img class="duster-image" src="<?php echo htmlspecialchars($model_duster['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_duster['titlu']); ?>">

        <div class="duster-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_duster['descriere']); ?></p>

            <div class="duster-specs">
                <?php foreach ($model_duster['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="duster-features">
                <h3>Dotări și Echipamente Cheie</h3>
                <ul class="features-list">
                    <?php foreach ($model_duster['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
        <img class="duster-image" src="../assets/images/info/duster-info2.jfif" alt="Dacia Duster - detalii suplimentare">
        <div class="duster-back-btn">
            <a href="../nav-autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
        </div>
    </div>

<?php include __DIR__ . '/../includes/footer.php'; ?>