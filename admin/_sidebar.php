<?php
// Sidebar navigasi area admin. Panggil admin_sidebar('nama-aktif').
function admin_sidebar($active = '')
{
    $items = [
        'dashboard'  => ['Dashboard', '/admin/dashboard.php'],
        'articles'   => ['Kelola Artikel', '/admin/articles.php'],
        'categories' => ['Kategori', '/admin/categories.php'],
        'comments'   => ['Komentar', '/admin/comments.php'],
        'users'      => ['Pengguna', '/admin/users.php'],
    ];
    echo '<nav class="side-menu">';
    echo '<div class="admin-menu-head"><div class="t">Admin Panel</div><div class="s">University Management</div></div>';
    foreach ($items as $key => [$label, $path]) {
        $cls = $key === $active ? ' class="active"' : '';
        echo '<a' . $cls . ' href="' . BASE_URL . $path . '">' . e($label) . '</a>';
    }
    echo '</nav>';
}
