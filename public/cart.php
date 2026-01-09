<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $products = [];
  $orderMessage = '';
  $orderError = '';

  $productQuery = 'SELECT id, name, image, price FROM products ORDER BY id ASC';
  if ($result = $conn->query($productQuery)) {
    while ($row = $result->fetch_assoc()) {
      $products[$row['id']] = $row;
    }
    $result->free();
  }

  $userID = $_SESSION['user_id'];
  $userStmt = $conn->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
  $userStmt->bind_param('i', $userID);
  $userStmt->execute();
  $userResult = $userStmt->get_result();
  $user = $userResult ? $userResult->fetch_assoc() : null;
  $userStmt->close();

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $deliveryType = $_POST['delivery_type'] ?? '';
    $city = trim($_POST['city'] ?? '');
    $postalCode = trim($_POST['zipcode'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $totalPrice = isset($_POST['total_price']) ? (float) $_POST['total_price'] : 0;
    $cartItemsRaw = $_POST['cart_items'] ?? '{}';
    $cartItems = json_decode($cartItemsRaw, true);

    if (!$cartItems || !is_array($cartItems)) {
      $orderError = 'Cart masih kosong.';
    } elseif ($deliveryType === '') {
      $orderError = 'Pilih tipe delivery.';
    } elseif ($totalPrice <= 0) {
      $orderError = 'Total harga tidak valid.';
    } else {
      $statusId = 1; // default PAID
      $insertOrder = $conn->prepare('INSERT INTO orders (user_id, status_id, delivery_type, total_price, address, city, postal_code, notes, phone, created, updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
      if ($insertOrder) {
        $address = $user['address'] ?? '';
        $phone = $user['phone'] ?? '';
        $insertOrder->bind_param(
          'iisdsssss',
          $userID,
          $statusId,
          $deliveryType,
          $totalPrice,
          $address,
          $city,
          $postalCode,
          $notes,
          $phone
        );

        if ($insertOrder->execute()) {
          $orderId = $conn->insert_id;
          $itemStmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price, created) VALUES (?, ?, ?, ?, NOW())');
          if ($itemStmt) {
            foreach ($cartItems as $productId => $quantity) {
              $productId = (int) $productId;
              $quantity = (int) $quantity;
              if ($productId <= 0 || $quantity <= 0) {
                continue;
              }
              $price = isset($products[$productId]) ? (int) $products[$productId]['price'] : 0;
              if ($price <= 0) {
                continue;
              }
              $itemStmt->bind_param('iiii', $orderId, $productId, $quantity, $price);
              $itemStmt->execute();
            }
            $itemStmt->close();
          }
          echo "<script>localStorage.removeItem('@cart');</script>";
          header('Location: order_detail.php?id=' . $orderId);
          exit;
        } else {
          $orderError = 'Gagal menyimpan pesanan.';
        }
        $insertOrder->close();
      } else {
        $orderError = 'Gagal menyiapkan query.';
      }
    }
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Cart</h2>
    <?php if ($orderMessage): ?>
      <p class="form-success"><?= $orderMessage; ?></p>
    <?php elseif ($orderError): ?>
      <p class="form-error"><?= $orderError; ?></p>
    <?php endif; ?>
    <br>

    <form class="cart-container" method="POST" action="cart.php">

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
            <input type="radio" name="delivery_type" value="pickup">
            Pickup
          </label>
          <label>
            <input type="radio" name="delivery_type" value="delivery">
            Delivery
          </label>
        </div>
        <div>
          <label>City</label>
          <input class="form-control" type="text" placeholder="add city here..." name="city"></input>
        </div>
        <div>
          <label>Postal Code</label>
          <input class="form-control" type="text" placeholder="add postal code here..." name="zipcode"></input>
        </div>
        <div>
          <label>Notes</label>
          <textarea placeholder="add notes here..." name="notes" rows="5"></textarea>
        </div>
        <br>
        <h4 id="total-price"></h4>
        <input type="hidden" value="" id="total-price-value" name="total_price">
        <input type="hidden" value="" id="cart-items-value" name="cart_items">
        <button class="btn-primary" type="submit">
          Checkout
        </button>
      </div>

    </form>
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
