<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $errors = [];
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare('SELECT id, password, role_id FROM users WHERE email = ?');

    if ($stmt === false) {
      $errors[] = 'Terjadi kesalahan. Coba beberapa saat lagi.';
    } else {
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result ? $result->fetch_assoc() : null;

      if (!$user || !password_verify($password, $user['password'])) {
        $errors[] = 'Email / password salah';
      } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];

        if ($user['role_id'] === 2) {
          // if user, go home
          header('Location: ' . $baseUrl);
        } else {
          // if admin go admin
          header('Location: ' . $adminUrl);
        }
        exit;
      }

      $stmt->close();
    }

    $conn->close();
  }
?>

<body>
  <section class="section auth-wrapper">
    <div class="auth-card">
      <h2 class="section-title">Masuk ke Dimsum Macan</h2>
      <?php if (!empty($errors)): ?>
        <p class="form-error">
          <?= implode('<br>', $errors); ?>
        </p>
      <?php endif; ?>

      <form class="auth-form" method="POST" action="login.php">
        <div class="form-group">
          <label for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input class="form-control" type="password" id="password" name="password" placeholder="******" required>
        </div>

        <div class="form-actions">
          <button class="btn-primary" type="submit">Login</button>
          <a class="auth-link" href="<?= $baseUrl?>register.php">Daftar</a>
        </div>
      </form>
    </div>
  </section>

</body>
</html>
