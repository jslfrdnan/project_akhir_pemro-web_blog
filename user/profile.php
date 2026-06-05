<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$uid = $_SESSION['user_id'];
$me  = current_user($pdo);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name    = trim($_POST['name'] ?? '');
    $faculty = trim($_POST['faculty'] ?? '');
    $bio     = trim($_POST['bio'] ?? '');
    $pass    = $_POST['password'] ?? '';

    if ($name === '') $errors[] = 'Nama wajib diisi.';

    // Avatar (opsional).
    $avatar = $me['avatar'];
    if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === 0) {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $type = mime_content_type($_FILES['avatar']['tmp_name']);
        if (!isset($allowed[$type])) {
            $errors[] = 'Format avatar harus JPG, PNG, atau WEBP.';
        } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran avatar maksimal 2 MB.';
        } else {
            $fname = 'avatar_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$type];
            move_uploaded_file($_FILES['avatar']['tmp_name'], UPLOAD_DIR . '/' . $fname);
            $avatar = $fname;
        }
    }

    if (!$errors) {
        $up = $pdo->prepare("UPDATE users SET name=?, faculty=?, bio=?, avatar=? WHERE id=?");
        $up->execute([$name, $faculty ?: null, $bio ?: null, $avatar, $uid]);
        if ($pass !== '') {
            if (strlen($pass) < 6) {
                set_flash('error', 'Profil tersimpan, tapi password minimal 6 karakter (tidak diubah).');
                redirect('/user/profile.php');
            }
            $pdo->prepare("UPDATE users SET password=? WHERE id=?")
                ->execute([password_hash($pass, PASSWORD_DEFAULT), $uid]);
        }
        set_flash('success', 'Profil berhasil diperbarui.');
        redirect('/user/profile.php');
    }
}

$page_title = 'Pengaturan Profil - UNSRAT Community Blog';
require __DIR__ . '/../includes/header.php';
?>
<div class="container">
  <div class="page-head"><h1>Pengaturan Profil</h1><p>Perbarui data publik yang tampil di halaman profilmu.</p></div>

  <?php if ($errors): ?><div class="flash flash-error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>

  <div class="form-card">
    <form method="post" enctype="multipart/form-data" data-validate>
      <?= csrf_field() ?>
      <div class="field">
        <label>Nama</label>
        <input type="text" name="name" value="<?= e($me['name']) ?>" required>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" value="<?= e($me['email']) ?>" disabled>
      </div>
      <div class="field">
        <label>Fakultas</label>
        <input type="text" name="faculty" value="<?= e($me['faculty']) ?>" placeholder="mis. Fakultas Teknik">
      </div>
      <div class="field">
        <label>Bio</label>
        <textarea name="bio" rows="4"><?= e($me['bio']) ?></textarea>
      </div>
      <div class="field">
        <label>Foto Profil (JPG/PNG/WEBP, maks 2 MB)</label>
        <input type="file" name="avatar" accept="image/*">
        <?php if ($me['avatar']): ?><p class="hint" style="margin-top:8px">Foto saat ini: <?= e($me['avatar']) ?></p><?php endif; ?>
      </div>
      <div class="field">
        <label>Password Baru <span style="color:var(--muted-light)">(kosongkan bila tidak diubah)</span></label>
        <input type="password" name="password">
      </div>
      <button class="btn btn-primary" type="submit">Simpan</button>
    </form>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
