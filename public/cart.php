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

  $deliveryType = '';
  $addressInput = '';
  $city = '';
  $postalCode = '';
  $notes = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $deliveryType = $_POST['delivery_type'] ?? '';
    $addressInput = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postalCode = trim($_POST['zipcode'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $totalPrice = isset($_POST['total_price']) ? (float) $_POST['total_price'] : 0;
    $cartItemsRaw = $_POST['cart_items'] ?? '{}';
    $cartItems = json_decode($cartItemsRaw, true);

    $requiresAddress = $deliveryType === 'delivery';

    if (!$cartItems || !is_array($cartItems)) {
      $orderError = 'Cart masih kosong.';
    } elseif ($deliveryType === '') {
      $orderError = 'Pilih tipe delivery.';
    } elseif ($requiresAddress && $addressInput === '') {
      $orderError = 'Alamat wajib diisi untuk delivery.';
    } elseif ($totalPrice <= 0) {
      $orderError = 'Total harga tidak valid.';
    } else {
      if (!$requiresAddress) {
        $addressInput = '';
        $city = '';
        $postalCode = '';
      }
      $statusId = 1; // default PAID
      $insertOrder = $conn->prepare('INSERT INTO orders (user_id, status_id, delivery_type, total_price, address, city, postal_code, notes, phone, created, updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
      if ($insertOrder) {
        $phone = $user['phone'] ?? '';
        $insertOrder->bind_param(
          'iisdsssss',
          $userID,
          $statusId,
          $deliveryType,
          $totalPrice,
          $addressInput,
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
          $_SESSION['clear_cart_after_order'] = true;
          header('Location: payment.php?id=' . $orderId);
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
          <strong>Contact</strong><br>
          Email: <?= $user['email'] ?><br>
          Phone: <?= $user['phone'] ?>
        </p>

        <label>Delivery Type</label>
        <div class="radio-group">
          <label>
            <input type="radio" name="delivery_type" value="pickup" <?= $deliveryType === 'pickup' ? 'checked' : ''; ?>>
            Pickup
          </label>
          <label>
            <input type="radio" name="delivery_type" value="delivery" <?= $deliveryType === 'delivery' ? 'checked' : ''; ?>>
            Delivery
          </label>
        </div>
        <div id="delivery-address-fields" style="<?= $deliveryType === 'delivery' ? '' : 'display:none;'; ?>">
          <div>
            <label>Address</label>
            <textarea class="form-control" rows="4" placeholder="add address here..." name="address"><?= htmlspecialchars($addressInput, ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>
          <div>
            <label>City</label>
            <input class="form-control" type="text" placeholder="add city here..." name="city" value="<?= htmlspecialchars($city, ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div>
            <label>Postal Code</label>
            <input class="form-control" type="text" placeholder="add postal code here..." name="zipcode" value="<?= htmlspecialchars($postalCode, ENT_QUOTES, 'UTF-8'); ?>">
          </div>
        </div>
        <div>
          <label>Notes</label>
          <textarea placeholder="add notes here..." name="notes" rows="5"><?= htmlspecialchars($notes, ENT_QUOTES, 'UTF-8'); ?></textarea>
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
    (function() {
      const addressSection = document.getElementById('delivery-address-fields');
      const deliveryRadios = document.querySelectorAll('input[name="delivery_type"]');
      const addressInputElm = document.querySelector('textarea[name="address"]');

      function toggleAddressSection() {
        const selected = document.querySelector('input[name="delivery_type"]:checked');
        const isDelivery = selected && selected.value === 'delivery';
        if (addressSection) {
          addressSection.style.display = isDelivery ? '' : 'none';
        }
        if (addressInputElm) {
          if (isDelivery) {
            addressInputElm.setAttribute('required', 'required');
          } else {
            addressInputElm.removeAttribute('required');
          }
        }
      }

      deliveryRadios.forEach(function(radio) {
        radio.addEventListener('change', toggleAddressSection);
      });
      toggleAddressSection();
    })();
  </script>
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
