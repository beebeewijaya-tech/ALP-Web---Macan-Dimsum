<?php
  $roleId = isset($_SESSION['role_id']) ? (int) $_SESSION['role_id'] : null;
  $isAdmin = $roleId !== null && $roleId !== 2;
?>
<header class="navbar">
  <div class="nav-left">
    <img src="<?= $imagePath?>logo.png" alt="Dimsum Macan" class="logo">
    <span class="brand-name"><?= $isAdmin ? 'Dimsum Macan - Admin' : 'Dimsum Macan'; ?></span>
  </div>
  <nav class="nav-menu">
    <?php if ($isAdmin): ?>
      <a href="<?= $adminUrl?>">Orders</a>
      <a href="<?= $adminUrl?>/admin_product.php">Products</a>
    <?php else: ?>
      <a href="<?= $baseUrl?>">Home</a>
      <a href="<?= $baseUrl?>cart.php">Cart</a>
      <a href="<?= $baseUrl?>order.php">Orders</a>
    <?php endif; ?>
    <a class="btn-logout" href="<?= $baseUrl?>logout.php">Logout</a>
  </nav>
</header>
