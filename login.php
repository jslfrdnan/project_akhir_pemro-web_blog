<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) redirect('/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && password_verify($pass, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['role']    = $u['role'];
        set_flash('success', 'Selamat datang, ' . $u['name'] . '!');
        redirect($u['role'] === 'admin' ? '/admin/dashboard.php' : '/index.php');
    }
    $error = 'Email atau password salah.';
}

$page_title = 'Login - UNSRAT Community Blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="auth-wrap">
    <div class="form-card">
      <h1>Masuk</h1>
      <p class="sub">Login untuk posting artikel & berkomentar.</p>

      <?php if ($error): ?><div class="flash flash-error"><?= e($error) ?></div><?php endif; ?>

      <form method="post" data-validate>
        <?= csrf_field() ?>
        <div class="field">
          <label>Email</label>
          <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" required>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Login</button>
      </form>
      <p class="muted-link">Belum punya akun? <a href="<?= BASE_URL ?>/register.php">Daftar di sini</a></p>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
