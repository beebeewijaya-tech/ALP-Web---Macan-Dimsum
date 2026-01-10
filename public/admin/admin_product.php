<?php include __DIR__ . '/../partials/metadata.php'; ?>
<?php
  $products = [];
  $message = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];
    $name = trim($_POST['name'] ?? '');
    $price = isset($_POST['price']) ? (int) $_POST['price'] : 0;

    if ($productId > 0 && $name !== '' && $price > 0) {
      $stmt = $conn->prepare('UPDATE products SET name = ?, price = ? WHERE id = ?');
      if ($stmt) {
        $stmt->bind_param('sii', $name, $price, $productId);
        $stmt->execute();
        $stmt->close();
        $_SESSION['product_message'] = 'Product updated.';
      } else {
        $_SESSION['product_message'] = 'Failed to update product.';
      }
      header('Location: admin_product.php');
      exit;
    }
  }

  if (!empty($_SESSION['product_message'])) {
    $message = $_SESSION['product_message'];
    unset($_SESSION['product_message']);
  }

  if ($result = $conn->query('SELECT id, name, image, price FROM products ORDER BY id')) {
    while ($row = $result->fetch_assoc()) {
      $products[] = $row;
    }
    $result->free();
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <!-- PRODUCT SECTION -->
  <section class="section">
    <h2 class="section-title">Product Management</h2>

    <?php if ($message): ?>
      <p class="form-success" style="text-align:center;"><?= $message; ?></p>
    <?php endif; ?>

    <div class="product-admin-header">
      <a href="admin_add_product.php">
        <button class="btn-primary" type="button">+ Add Product</button>
      </a>
    </div>

    <?php if (empty($products)): ?>
      <p style="text-align:center;">Belum ada produk.</p>
    <?php else: ?>
      <div class="admin-product-list">
        <?php foreach ($products as $product): ?>
          <div class="admin-product-card">
            <img src="<?= $imagePath . $product['image']; ?>" alt="<?= $product['name']; ?>" class="admin-product-img">

            <div class="admin-product-info">
              <h3><?= $product['name']; ?></h3>
              <p class="price">Rp <?= number_format((float) $product['price'], 0, ',', '.'); ?></p>
            </div>

            <div class="admin-product-action">
              <a href="admin_edit_product.php?id=<?= (int) $product['id']; ?>">
                <button class="btn-primary" type="button">Edit Detail</button>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

</body>
</html>
