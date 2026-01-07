<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $products = [];

  $query = 'SELECT name, image, price FROM product ORDER BY id ASC';
  if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
      $products[] = $row;
    }
    $result->free();
  }

  $conn->close();
?>
<body>

  <?php include __DIR__ . '/partials/header.php'; ?>

  <!-- Menu Andalan -->
  <section class="section">
    <h2 class="section-title">Semua Menu</h2>

    <?php if (empty($products)): ?>
      <p style="text-align:center;">Belum ada produk yang tersedia.</p>
    <?php else: ?>
      <div class="product-grid">
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <img src="<?= $imagePath . htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
              alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <h3><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="price">
              Rp <?= number_format((float) $product['price'], 0, ',', '.'); ?>
            </p>
            <button class="btn-primary">Add to Cart</button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</body>
</html>
