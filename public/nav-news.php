<?php
include __DIR__ . '/includes/header.php';
?>

<h1 class="page-title">Știri</h1>
<p class="page-subtitle">Ultimele noutăți din lumea Dacia.</p>

<div class="news-grid">
    <?php
    require_once __DIR__ . '/../config/database.php';
    $stmt = $conn->query("SELECT * FROM Stiri ORDER BY data_publicarii DESC");
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($news as $item):
        $imgSrc = file_exists(__DIR__ . '/' . $item['imagine'])
            ? $item['imagine']
            : 'https://picsum.photos/400/250?random=' . $item['id_stire'];
        ?>
        <article class="news-card">
            <img src="<?= htmlspecialchars($imgSrc) ?>"
                alt="<?= htmlspecialchars($item['alt_imagine'] ?? 'Stire Dacia') ?>">
            <h3><?= htmlspecialchars($item['titlu']) ?></h3>
            <p><?= htmlspecialchars($item['descriere']) ?> <a href="stire.php?id=<?= $item['id_stire'] ?>">...Citeste mai
                    mult</a></p>
        </article>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>