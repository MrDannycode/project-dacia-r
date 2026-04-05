<?php
include "includes/header.php";

$news = [
    [
        'image' => 'assets/images/news-lansare.jpg',
        'alt' => 'Noutate Dacia',
        'title' => 'Lansare noua Dacia',
        'description' => 'Descoperă ultimele noutăți din gama Dacia. Modele noi, tehnologii inovatoare și oferte speciale pentru tine.',
        'link' => 'news1.php',
    ],
    [
        'image' => 'assets/images/news-primavara.jpg',
        'alt' => 'Oferte promoționale',
        'title' => 'Oferte de primăvară',
        'description' => 'Profita de promoțiile sezonului. Condiții avantajoase de finanțare și reduceri exclusive la modelele selectate.',
        'link' => 'news2.php',
    ],
    [
        'image' => 'assets/images/news-test-drive.webp',
        'alt' => 'Eveniment Dacia',
        'title' => 'Test drive gratuit',
        'description' => 'Înscrie-te la o sesiune de test drive și experimentează personal calitățile vehiculelor Dacia.',
        'link' => 'news3.php',
    ],
    [
        'image' => 'assets/images/news-hybrid.jpg',
        'alt' => 'Hybrid-G 150 4x4',
        'title' => 'Hybrid-G 150 4x4 pe Dacia Duster (2026)',
        'description' => 'Primul sistem din lume care combină hibridul, tracțiunea integrală și transmisia automată cu alimentare benzină-GPL.',
        'link' => 'news4.php',
    ],
    [
        'image' => 'assets/images/news-bigster.webp',
        'alt' => 'Dacia Bigster',
        'title' => 'Bigster devine realitate',
        'description' => 'Noul SUV Dacia Bigster aduce o prezență impunătoare, spațiu generos și motorizări noi.',
        'link' => 'news5.php',
    ],
    [
        'image' => 'assets/images/news-dakar.webp',
        'alt' => 'Dacia Sandrider',
        'title' => 'Dacia participă la Dakar',
        'description' => 'Echipa Dacia Sandriders se pregătește pentru cea mai dură competiție de motorsport din lume.',
        'link' => 'news6.php',
    ],
    [
        'image' => 'assets/images/news-spring.webp',
        'alt' => 'Dacia Spring facelift',
        'title' => 'Facelift major pentru Spring',
        'description' => 'Cel mai accesibil model electric se înnoiește cu un design modern și conectivitate de top.',
        'link' => 'news7.php',
    ],
];
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
            $imgSrc = file_exists(__DIR__ . '/' . $item['image'])
                ? $item['image']
                : 'https://picsum.photos/400/250?random=' . ($index + 1);
            ?>
            <article class="news-card">
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($item['alt']) ?>">
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p><?= htmlspecialchars($item['description']) ?> <a href="<?= htmlspecialchars($item['link']) ?>">...Citeste
                        mai mult</a></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<div class="intro-image">
    <img src="assets/images/dacia-intro.jpg" alt="Dacia" onerror="this.style.display='none'">
</div>

<?php include "includes/footer.php"; ?>