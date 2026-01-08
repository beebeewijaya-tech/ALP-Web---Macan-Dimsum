<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $products = [];

  $query = 'SELECT id, name, image, price FROM products ORDER BY id ASC';
  if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
      $products[$row['id']] = $row;
    }
    $result->free();
  }

  $userID = $_SESSION['user_id'];
  $query = $conn->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
  $query->bind_param('i', $userID);
  $query->execute();
  if ($result = $query->get_result()) {
    $user = $result->fetch_assoc();
    echo $user['phone'];
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Cart</h2>

    <div class="cart-container">

      <!-- Kiri: Cart Items -->
      <div class="cart-items-column">
        <div class="cart-items" id="cart-items"></div>
        <p id="cart-empty" class="cart-empty">Belum ada item di cart.</p>
      </div>

      <!-- Kanan: Order Details -->
      <div class="order-summary">
        <h3>Order Details</h3>

        <p>
          <strong>Address</strong><br>
          <?= $user['address'] ?>
        </p>

        <p>
          <strong>Contact</strong><br>
          Email: <?= $user['email'] ?><br>
          Phone: <?= $user['phone'] ?>
        </p>

        <label>Delivery Type</label>
        <div class="radio-group">
          <label>
            <input type="radio" name="delivery"> Pickup
          </label>
          <label>
            <input type="radio" name="delivery"> Delivery
          </label>
        </div>

        <label>Notes</label>
        <textarea placeholder="add notes here..." name="notes"></textarea>

        <button class="btn-primary" type="button">
          Checkout
        </button>
      </div>

    </div>
  </section>

  <script>
    // goals are
    // 1. get PHP array from the query products and encode it into JSON and save to JS global variable
    // 2. get PHP imagepath that we save at partials and save to JS global variable
    window.PRODUCT_CATALOG = <?= json_encode($products, JSON_UNESCAPED_UNICODE); ?>;
    window.IMAGE_BASE_PATH = '<?= $imagePath; ?>';
  </script>
  <script src="<?= $baseUrl ?>assets/js/cart-page.js"></script>
</body>
</html>
