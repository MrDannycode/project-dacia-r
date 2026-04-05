<?php session_start(); ?>
<?php include __DIR__ . '/includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/spring.css?v=<?= time() ?>">

<?php
$model_spring = [
    "titlu" => "Noul Dacia Spring",
    "subtitlu" => "Mașina electrică de oraș, accesibilă și conectată",
    "descriere" => "Cel mai accesibil model electric din Europa a primit o transformare majoră. Noul Dacia Spring adoptă limbajul robust de design al mărcii, aducând un interior complet redesenat, mai multă tehnologie și un spațiu de depozitare optimizat. Cu o greutate redusă și o eficiență remarcabilă, Spring rămâne soluția perfectă pentru mobilitatea urbană cu zero emisii, fiind incredibil de agilă și ușor de parcat.",
    "imagine" => "assets/images/spring-info1.jpg",
    "specificatii" => [
        "Motorizare" => "100% Electric (45 CP / 65 CP)",
        "Autonomie (WLTP)" => "Până la 225 km",
        "Capacitate Baterie" => "26.8 kWh"
    ],
    "dotari" => [
        "Încărcare bi-direcțională V2L (poate alimenta alte dispozitive)",
        "Sistem multimedia Media Nav Live cu ecran de 10\"",
        "Tablou de bord digital personalizabil de 7\"",
        "Asistență la menținerea benzii de rulare (LKA)",
        "Sistem de recunoaștere a panourilor rutiere",
        "Portbagaj generos de 308 litri (cel mai mare din clasă)",
        "Spațiu de depozitare frontal suplimentar (Frunk) opțional",
        "Sistem de accesorii inteligente YouClip",
        "Aplicația My Dacia (verificare baterie, precondiționare climă)",
        "Design tip crossover cu jante de 15\""
    ]
];
?>

<div class="spring-content-wrapper">
    <div class="spring-hero">
        <h1><?php echo htmlspecialchars($model_spring['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_spring['subtitlu']); ?></p>
    </div>

    <div class="spring-card">
        <img class="spring-image" src="<?php echo htmlspecialchars($model_spring['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_spring['titlu']); ?>">

        <div class="spring-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_spring['descriere']); ?></p>

            <div class="spring-specs">
                <?php foreach ($model_spring['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="spring-features">
                <h3>Tehnologie și Echipamente 100% Electrice</h3>
                <ul class="features-list">
                    <?php foreach ($model_spring['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <img class="spring-image" src="assets/images/spring-info2.jpg" alt="Dacia Spring - detalii suplimentare">
        <div class="spring-back-btn">
            <a href="autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
        </div>
    </div>

<?php include __DIR__ . '/includes/footer.php'; ?>