<?php
include __DIR__ . '/includes/header.php';
?>

<h1 class="page-title">Știri</h1>
<p class="page-subtitle">Ultimele noutăți din lumea Dacia.</p>

<div class="news-grid">
    <?php
    $news = [
        [
            'image' => 'assets/images/news/1.jpg',
            'alt' => 'Noutate Dacia',
            'title' => 'Lansare nouă Dacia',
            'description' => 'Descoperă ultimele noutăți din gama Dacia. Modele noi, tehnologii inovatoare și oferte speciale pentru tine.',
            'link' => 'news1.php',
        ],
        [
            'image' => 'assets/images/news/2.jpg',
            'alt' => 'Oferte promoționale',
            'title' => 'Oferte de primăvară',
            'description' => 'Profita de promoțiile sezonului. Condiții avantajoase de finanțare și reduceri exclusive la modelele selectate.',
            'link' => 'news2.php',
        ],
        [
            'image' => 'assets/images/news/3.jpg',
            'alt' => 'Eveniment Dacia',
            'title' => 'Test drive gratuit',
            'description' => 'Înscrie-te la o sesiune de test drive și experimentează personal calitățile vehiculelor Dacia.',
            'link' => 'news3.php',
        ],
        [
            'image' => 'assets/images/news/4.jpg',
            'alt' => 'Hybrid-G 150 4x4',
            'title' => 'Hybrid-G 150 4x4 pe Dacia Duster (2026)',
            'description' => 'Primul sistem din lume care combină hibridul, tracțiunea integrală și transmisia automată cu alimentare benzină-GPL.',
            'link' => 'news4.php',
        ],
    ];

    foreach ($news as $index => $item):
        $imgSrc = file_exists(__DIR__ . '/' . $item['image']) 
            ? $item['image'] 
            : 'https://picsum.photos/400/250?random=' . ($index + 1);
    ?>
    <article class="news-card">
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($item['alt']) ?>">
        <h3><?= htmlspecialchars($item['title']) ?></h3>
        <p><?= htmlspecialchars($item['description']) ?> <a href="<?= htmlspecialchars($item['link']) ?>">...Citeste mai mult</a></p>
    </article>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
