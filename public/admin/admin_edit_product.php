<?php include __DIR__ . '/../partials/metadata.php'; ?>
<?php
  $message = '';
  $productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  $product = null;

  if ($productId > 0) {
    $stmt = $conn->prepare('SELECT id, name, price, image FROM products WHERE id = ? LIMIT 1');
    if ($stmt) {
      $stmt->bind_param('i', $productId);
      $stmt->execute();
      $result = $stmt->get_result();
      $product = $result ? $result->fetch_assoc() : null;
      $stmt->close();
    }
  }

  if (!$product) {
    $conn->close();
    header('Location: admin_product.php');
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = isset($_POST['price']) ? (int) $_POST['price'] : 0;
    $imageFileName = $product['image'];

    if ($name === '' || $price <= 0) {
      $message = 'Nama dan harga wajib diisi.';
    } else {
      if (!empty($_FILES['image']['name'])) {
        $uploadDir = realpath(__DIR__ . '/../assets/images');
        if ($uploadDir !== false) {
          $uploadDir .= '/';
          $originalName = basename($_FILES['image']['name']);
          $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
          $safeName = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($originalName, PATHINFO_FILENAME));
          $imageFileName = uniqid('product_', true) . '-' . $safeName . ($ext ? '.' . $ext : '');
          $targetPath = $uploadDir . $imageFileName;
          if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $message = 'Gagal mengunggah gambar baru.';
          }
        } else {
          $message = 'Direktori upload tidak ditemukan.';
        }
      }

      if ($message === '') {
        $updateStmt = $conn->prepare('UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?');
        if ($updateStmt) {
          $updateStmt->bind_param('sisi', $name, $price, $imageFileName, $productId);
          if ($updateStmt->execute()) {
            $_SESSION['product_message'] = 'Produk berhasil diperbarui.';
            $updateStmt->close();
            $conn->close();
            header('Location: admin_product.php');
            exit;
          } else {
            $message = 'Gagal memperbarui produk.';
          }
          $updateStmt->close();
        }
      }
    }
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Edit Product</h2>

    <?php if ($message): ?>
      <p class="form-error"><?= $message; ?></p>
    <?php endif; ?>

    <div class="admin-form-container">

      <form class="product-form" method="POST" enctype="multipart/form-data">

        <label>Product Name</label>
        <input type="text" name="name" value="<?= $product['name']; ?>" required>

        <label>Price</label>
        <input type="number" name="price" value="<?= (int) $product['price']; ?>" required>

        <label>Current Image</label>
        <img src="<?= $imagePath . $product['image']; ?>" alt="<?= $product['name']; ?>" style="width:160px;">

        <label>Change Product Image</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn-primary">Update Product</button>

      </form>

    </div>
  </section>

</body>
</html>
