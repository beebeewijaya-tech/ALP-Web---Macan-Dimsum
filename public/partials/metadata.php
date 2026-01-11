<?php
  $baseUrl = '/';
  $adminUrl = '/admin';
  $imagePath = '/assets/images/';
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
?>
<?php include __DIR__ . '/session.php'; ?>
<?php include __DIR__ . '/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dimsum Macan</title>
  <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/style.css">
  <?php if (!empty($_SESSION['clear_cart_after_order'])): ?>
    <script>
      window.addEventListener('DOMContentLoaded', function () {
        localStorage.removeItem('@cart');
      });
    </script>
  <?php
    unset($_SESSION['clear_cart_after_order']);
  endif; ?>
</head>
