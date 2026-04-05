<?php
include "includes/header.php";

require_once __DIR__ . '/../config/database.php';
$stmt = $conn->query("SELECT * FROM Stiri ORDER BY data_publicarii DESC LIMIT 10");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="page-title">Bun venit la Dacia!</h1>
<p class="page-subtitle">Descoperă gama noastră de automobile și cele mai recente noutăți.</p>

<section class="news-carousel" aria-label="Știri">
    <button class="carousel-arrow prev" type="button" data-carousel="prev" aria-label="Știri: anterior">
        ‹
    </button>
    <button class="carousel-arrow next" type="button" data-carousel="next" aria-label="Știri: următor">
        ›
    </button>
    <div class="news-grid">
        <?php foreach ($news as $index => $item):
            $imgSrc = !empty($item['imagine']) && file_exists(__DIR__ . '/' . $item['imagine'])
                ? $item['imagine']
                : 'https://picsum.photos/400/250?random=' . ($item['id_stire'] ?? $index);
            ?>
            <article class="news-card">
                <img src="<?= htmlspecialchars($imgSrc) ?>"
                    alt="<?= htmlspecialchars($item['alt_imagine'] ?? 'Stire Dacia') ?>">
                <h3><?= htmlspecialchars($item['titlu']) ?></h3>
                <p><?= htmlspecialchars($item['descriere']) ?> <a href="stire.php?id=<?= $item['id_stire'] ?>">...Citeste
                        mai mult</a></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<div class="intro-image">
    <img src="assets/images/dacia-intro.jpg" alt="Dacia" onerror="this.style.display='none'">
</div>

<?php include "includes/footer.php"; ?>