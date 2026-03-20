<?php
include __DIR__ . '/includes/header.php';
?>

<h1>Știri</h1>

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

<style>
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}
.news-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}
.news-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}
.news-card h3 {
    margin: 1rem 1rem 0.5rem;
    font-size: 1.25rem;
}
.news-card p {
    margin: 0 1rem 1rem;
    color: #555;
    font-size: 0.9rem;
    line-height: 1.4;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
