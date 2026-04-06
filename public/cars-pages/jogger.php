<?php session_start(); ?>
<?php 
$is_subdir = true;
include __DIR__ . '/../includes/header.php'; 
?>

<link rel="stylesheet" href="../assets/css/jogger.css?v=<?= time() ?>">

<?php
$model_jogger = [
    "titlu" => "Noul Dacia Jogger",
    "subtitlu" => "Mașina de familie polivalentă, cu până la 7 locuri",
    "descriere" => "Dacia Jogger reinventează mașina de familie. Cu lungimea unui break, spațiul interior al unui monovolum și liniile robuste ale unui SUV, Jogger este o adevărată mașină polivalentă. Construit pe platforma modernă CMF-B, oferă o experiență de condus excelentă, un spațiu modular inegalabil pentru până la 7 pasageri adulți și opțiunea unei motorizări hibride de top.",
    "imagine" => "../assets/images/info/jogger-info1.jfif",
    "specificatii" => [
        "Motorizare" => "HYBRID 140 / ECO-G 100 / TCe 110",
        "Număr locuri" => "5 sau 7 locuri",
        "Volum portbagaj" => "Până la 708 Litri (configurația cu 5 locuri)"
    ],
    "dotari" => [
        "Sistem de propulsie complet hibrid (pentru HYBRID 140)",
        "Bare de pavilion modulare (transformabile în bare transversale)",
        "3 rânduri de scaune complet modulabile",
        "Faruri Eco-LED cu semnătură în formă de Y",
        "Sistem Media Display 8 inch sau Media Nav",
        "Măsuțe rabatabile pe spătarele scaunelor din față",
        "Sistem de frânare de urgență automat (AEBS)",
        "Card mâini libere și frână de parcare electrică",
        "Senzori de parcare față/spate și cameră marșarier",
        "Senzor de unghi mort"
    ]
];
?>

<div class="jogger-content-wrapper">
    <div class="jogger-hero">
        <h1><?php echo htmlspecialchars($model_jogger['titlu']); ?></h1>
        <p><?php echo htmlspecialchars($model_jogger['subtitlu']); ?></p>
    </div>

    <div class="jogger-card">
        <img class="jogger-image" src="<?php echo htmlspecialchars($model_jogger['imagine']); ?>"
            alt="<?php echo htmlspecialchars($model_jogger['titlu']); ?>">

        <div class="jogger-details">
            <h2>Prezentare Generală</h2>
            <p><?php echo htmlspecialchars($model_jogger['descriere']); ?></p>

            <div class="jogger-specs">
                <?php foreach ($model_jogger['specificatii'] as $cheie => $valoare): ?>
                    <div class="spec-item">
                        <strong><?php echo htmlspecialchars($valoare); ?></strong>
                        <span><?php echo htmlspecialchars($cheie); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="jogger-features">
                <h3>Dotări și Echipamente</h3>
                <ul class="features-list">
                    <?php foreach ($model_jogger['dotari'] as $dotare): ?>
                        <li><?php echo htmlspecialchars($dotare); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <img class="jogger-image" src="../assets/images/info/jogger-info2.jfif" alt="Dacia Jogger - detalii suplimentare">
    </div>
    <div class="jogger-back-btn">
        <a href="../nav-autoturisme.php" class="btn-account">Înapoi la Autoturisme</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>