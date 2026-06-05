<?php
// Sidebar navigasi area user. Panggil user_sidebar('nama-aktif').
function user_sidebar($active = '')
{
    $items = [
        'dashboard'    => ['Dashboard', '/user/dashboard.php'],
        'articles'     => ['Artikel Saya', '/user/articles.php'],
        'article-form' => ['Tulis Artikel', '/user/article-form.php'],
        'profile'      => ['Profil', '/user/profile.php'],
    ];
    echo '<nav class="side-menu">';
    foreach ($items as $key => [$label, $path]) {
        $cls = $key === $active ? ' class="active"' : '';
        echo '<a' . $cls . ' href="' . BASE_URL . $path . '">' . e($label) . '</a>';
    }
    echo '</nav>';
}
