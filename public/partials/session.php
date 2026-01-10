<?php
  session_start();

  $publicRoutes = ['login.php', 'register.php'];
  $currentPage = basename($_SERVER['SCRIPT_NAME']);
  $base = isset($baseUrl) ? rtrim($baseUrl, '/') : '';
  $loginUrl = $base . '/login.php';

  if (!isset($_SESSION['user_id']) && !in_array($currentPage, $publicRoutes, true)) {
    // if didn't has session
    // when visit home
    // will push back to login
    header('Location: ' . $loginUrl);
    exit;
  }

  if (isset($_SESSION['user_id']) && in_array($currentPage, $publicRoutes, true)) {
    // if its already has session
    // when visit register or login
    // will push back to home

    $roleId = isset($_SESSION['role_id']) ? (int) $_SESSION['role_id'] : null;
    if ($roleId === 2) {
      // if user, go home
      header('Location: ' . $baseUrl);
    } else {
      // if admin go admin
      header('Location: ' . $adminUrl);
    }

    exit;
  }
?>
