<?php
  session_start();
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
  }
  session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keluar...</title>
  <script>
    window.addEventListener('DOMContentLoaded', function () {
      try {
        localStorage.removeItem('@cart');
      } catch (err) {
        console.warn('Failed to clear local storage', err);
      }
      window.location.href = 'login.php';
    });
  </script>
</head>
<body>
  <p>Keluar dari aplikasi... <a href="login.php">Kembali ke login</a></p>
</body>
</html>
