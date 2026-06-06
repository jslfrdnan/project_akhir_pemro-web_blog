<?php
// Partial kartu artikel. Membutuhkan $a (baris artikel + kolom: kategori, slug, title, excerpt, content, cover_image, created_at).
?>
<article class="acard">
  <?php if (!empty($a['cover_image'])): ?>
    <img class="acard-cover" src="<?= UPLOAD_URL . '/' . e($a['cover_image']) ?>" alt="">
  <?php else: ?>
    <div class="acard-cover"></div>
  <?php endif; ?>
  <div class="acard-body">
    <div><span class="chip"><?= e($a['kategori']) ?></span></div>
    <h3 class="acard-title">
      <a href="<?= BASE_URL ?>/article.php?slug=<?= e($a['slug']) ?>"><?= e($a['title']) ?></a>
    </h3>
    <p class="acard-excerpt"><?= e(excerpt_text($a['excerpt'] ?: $a['content'], 110)) ?></p>
    <div class="acard-meta">
      <span><?= e(time_ago($a['created_at'])) ?></span>
      <span>&#128065; <?= (int)$a['views'] ?></span>
    </div>
  </div>
</article>
