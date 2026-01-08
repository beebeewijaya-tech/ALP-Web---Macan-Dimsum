<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $errors = [];
  $email = '';
  $address = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $address = trim($_POST["address"] ?? '');
    $phone = trim($_POST["phone"] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Email tidak valid';
    }

    if (strlen($password) < 6) {
      $errors[] = 'Password minimal 6 karakter';
    }

    if ($phone === '') {
      $errors[] = 'No Hp wajib diisi';
    }

    if ($address === '') {
      $errors[] = 'Alamat wajib diisi';
    }

    if (empty($errors)) {
      $passwordHash = password_hash($password, PASSWORD_BCRYPT);
      $insertStmt = $conn->prepare('INSERT INTO users (email, password, role_id, address, phone, created, updated) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');

      if ($insertStmt === false) {
        $errors[] = 'Query gagal disiapkan';
      } else {
        $defaultRoleId = 2; // role user
        $insertStmt->bind_param('ssiss', $email, $passwordHash, $defaultRoleId, $address, $phone);

        if ($insertStmt->execute()) {
          header('Location: login.php');
          exit;
        } else {
          if ($conn->errno === 1062) {
            // 1062 is a code for SQL error
            // that detecting if CONFLICT or duplicate key error, something like syserrcode
            $errors[] = 'Email sudah terdaftar, silakan login';
          } else {
            $errors[] = 'Gagal mendaftarkan akun. Coba lagi';
          }
        }

        $insertStmt->close();
      }
    }
  }
?>
<body>
  <section class="section auth-wrapper">
    <div class="auth-card">
      <h2 class="section-title">Daftar akun baru</h2>
      <?php if (!empty($errors)): ?>
        <p class="form-error">
          <?= implode('<br>', array_map(function ($error) {
            return htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
          }, $errors)); ?>
        </p>
      <?php endif; ?>

      <form class="auth-form" method="post" action="./register.php">
        <div class="form-group">
          <label for="email">Email</label>
          <input class="form-control" type="email" id="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input class="form-control" type="password" id="password" name="password" placeholder="******" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone</label>
          <input class="form-control" type="phone" id="phone" name="phone" placeholder="+628123456789" value="<?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="address">Alamat</label>
          <textarea class="form-control" rows="5" id="address" name="address" required><?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>

        <div class="form-actions">
          <button class="btn-primary" type="submit">Daftar</button>
          <a class="auth-link" href="./login.php">Sudah punya akun?</a>
        </div>
      </form>
    </div>
  </section>

</body>
</html>
