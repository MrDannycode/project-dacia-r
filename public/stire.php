<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM Stiri WHERE id_stire = :id");
$stmt->execute([':id' => $id]);
$stire = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$stire) {
    header("Location: nav-news.php");
    exit();
}

include __DIR__ . '/includes/header.php';
?>
<main class="news-detail-page">
    <div class="content-container" style="max-width: 800px; margin: 2rem auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h1><?= htmlspecialchars($stire['titlu']) ?></h1>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 20px;">Publicat la: <?= htmlspecialchars(date('d.m.Y H:i', strtotime($stire['data_publicarii']))) ?></p>
        
        <?php if (!empty($stire['imagine'])): ?>
            <img src="<?= htmlspecialchars($stire['imagine']) ?>" alt="<?= htmlspecialchars($stire['alt_imagine'] ?? 'Imagine Dacia') ?>" style="width: 100%; height: auto; border-radius: 8px; margin-bottom: 20px;">
        <?php endif; ?>
        
        <div class="news-content" style="line-height: 1.6; font-size: 1.1rem; color: #333;">
            <?= $stire['continut'] ?>
        </div>
        
        <p style="margin-top: 2rem;"><a href="nav-news.php" class="btn" style="background:#f75d34; color:#fff; padding: 10px 15px; text-decoration:none; border-radius:4px; font-weight: bold;">&larr; Înapoi la Știri</a></p>
    </div>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
