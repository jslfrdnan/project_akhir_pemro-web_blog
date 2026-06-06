<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Kontak - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="page-head">
    <div class="breadcrumb"><a href="<?= BASE_URL ?>/index.php">Home</a> &rsaquo; Kontak</div>
    <h1>Kontak</h1>
  </div>
  <div class="prose" style="margin-bottom:50px;">
    <p>Punya pertanyaan, saran, atau ingin berkolaborasi? Hubungi tim redaksi melalui kanal berikut.</p>
    <h2>Alamat</h2>
    <p>Universitas Sam Ratulangi, Jl. Kampus Unsrat, Bahu, Kec. Malalayang, Kota Manado,
       Sulawesi Utara 95115.</p>
    <h2>Email</h2>
    <p>redaksi@unsrat.ac.id</p>
    <h2>Jam Operasional</h2>
    <p>Senin &ndash; Jumat, 08.00 &ndash; 16.00 WITA.</p>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
