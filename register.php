<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) redirect('/index.php');

$errors = [];
$old = ['name' => '', 'email' => '', 'role' => 'mahasiswa', 'faculty' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $old['name']    = trim($_POST['name'] ?? '');
    $old['email']   = trim($_POST['email'] ?? '');
    $old['role']    = $_POST['role'] ?? 'mahasiswa';
    $old['faculty'] = trim($_POST['faculty'] ?? '');
    $pass           = $_POST['password'] ?? '';

    if ($old['name'] === '')                 $errors[] = 'Nama wajib diisi.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (strlen($pass) < 6)                   $errors[] = 'Password minimal 6 karakter.';
    if (!in_array($old['role'], ['dosen', 'mahasiswa'])) $errors[] = 'Peran tidak valid.';

    if (!$errors) {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$old['email']]);
        if ($check->fetch()) {
            $errors[] = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $pdo->prepare(
              "INSERT INTO users (name, email, password, role, faculty) VALUES (?, ?, ?, ?, ?)");
            $ins->execute([$old['name'], $old['email'], $hash, $old['role'], $old['faculty'] ?: null]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role']    = $old['role'];
            set_flash('success', 'Akun berhasil dibuat. Selamat datang!');
            redirect('/index.php');
        }
    }
}

$page_title = 'Daftar - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="auth-wrap">
    <div class="form-card">
      <h1>Daftar Akun</h1>
      <p class="sub">Untuk dosen & mahasiswa Universitas Sam Ratulangi.</p>

      <?php if ($errors): ?>
        <div class="flash flash-error"><?= e(implode(' ', $errors)) ?></div>
      <?php endif; ?>

      <form method="post" data-validate>
        <?= csrf_field() ?>
        <div class="field">
          <label>Nama Lengkap</label>
          <input type="text" name="name" value="<?= e($old['name']) ?>" required>
        </div>
        <div class="field">
          <label>Email</label>
          <input type="email" name="email" value="<?= e($old['email']) ?>" required>
        </div>
        <div class="field">
          <label>Peran</label>
          <select name="role">
            <option value="mahasiswa" <?= $old['role']==='mahasiswa'?'selected':'' ?>>Mahasiswa</option>
            <option value="dosen" <?= $old['role']==='dosen'?'selected':'' ?>>Dosen</option>
          </select>
        </div>
        <div class="field">
          <label>Fakultas <span style="color:var(--muted-light)">(opsional)</span></label>
          <input type="text" name="faculty" value="<?= e($old['faculty']) ?>" placeholder="mis. Fakultas Teknik">
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Daftar</button>
      </form>
      <p class="muted-link">Sudah punya akun? <a href="<?= BASE_URL ?>/login.php">Login</a></p>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
