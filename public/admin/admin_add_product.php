<?php include __DIR__ . '/../partials/metadata.php'; ?>
<?php
  $message = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = isset($_POST['price']) ? (int) $_POST['price'] : 0;
    $description = trim($_POST['description'] ?? '');
    $imageFileName = '';

    if ($name === '' || $price <= 0) {
      $message = 'Nama dan harga wajib diisi.';
    } elseif (empty($_FILES['image']['name'])) {
      $message = 'File gambar wajib diunggah.';
    } else {
      $uploadDir = realpath(__DIR__ . '/../assets/images') . '/';
      if ($uploadDir) {
        $originalName = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $safeName = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($originalName, PATHINFO_FILENAME));
        $imageFileName = uniqid('product_', true) . '-' . $safeName . ($ext ? '.' . $ext : '');
        $targetPath = $uploadDir . $imageFileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
          $stmt = $conn->prepare('INSERT INTO products (name, price, image, created) VALUES (?, ?, ?, NOW())');
          if ($stmt) {
            $stmt->bind_param('sis', $name, $price, $imageFileName);
            if ($stmt->execute()) {
              $message = 'Produk berhasil ditambahkan.';
            } else {
              $message = 'Gagal menambahkan produk.';
            }
            $stmt->close();
          }
        } else {
          $message = 'Gagal mengunggah gambar.';
        }
      } else {
        $message = 'Direktori upload tidak ditemukan.';
      }
    }
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Add New Product</h2>

    <?php if ($message): ?>
      <p class="<?= strpos($message, 'berhasil') !== false ? 'form-success' : 'form-error'; ?>">
        <?= $message; ?>
      </p>
    <?php endif; ?>

    <div class="admin-form-container">

      <form class="product-form" method="POST" enctype="multipart/form-data">

        <label>Product Name</label>
        <input type="text" name="name" placeholder="Enter product name">

        <label>Price</label>
        <input type="number" name="price" placeholder="Enter price">

        <label>Description</label>
        <textarea rows="4" name="description" placeholder="Enter product description"></textarea>

        <label>Product Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn-primary">Save Product</button>

      </form>

    </div>
  </section>

</body>
</html>
