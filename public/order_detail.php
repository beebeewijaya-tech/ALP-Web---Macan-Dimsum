<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  $userID = $_SESSION['user_id'];
  $order = null;
  $orderItems = [];

  $statusClassMap = [
    'unpaid' => 'status-unpaid',
    'paid' => 'status-paid',
    'processed' => 'status-processed',
    'delivered' => 'status-delivered'
  ];

  if ($orderId > 0) {
    $orderStmt = $conn->prepare('SELECT o.*, s.status_type, u.email FROM orders o JOIN statuses s ON s.id = o.status_id JOIN users u ON u.id = o.user_id WHERE o.id = ? AND o.user_id = ? LIMIT 1');
    $orderStmt->bind_param('ii', $orderId, $userID);
    $orderStmt->execute();
    $orderResult = $orderStmt->get_result();
    $order = $orderResult ? $orderResult->fetch_assoc() : null;
    $orderStmt->close();

    if ($order) {
      $itemStmt = $conn->prepare('SELECT oi.quantity, oi.price, p.name, p.image FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?');
      $itemStmt->bind_param('i', $orderId);
      $itemStmt->execute();
      $itemsResult = $itemStmt->get_result();
      while ($row = $itemsResult->fetch_assoc()) {
        $orderItems[] = $row;
      }
      $itemStmt->close();
    }
  }

  $conn->close();
?>
<body>

  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Order details</h2>

    <?php if (!$order): ?>
      <p style="text-align:center;">Order tidak ditemukan.</p>
    <?php else: ?>
      <div class="order-detail-container">

        <div>
          <?php if (strtolower($order['status_type']) === 'unpaid'): ?>
            <div class="payment-card" style="margin-bottom:24px;">
              <h3>Scan QR to Pay</h3>
              <img src="<?= $imagePath ?>qris.png" alt="QR Payment" class="qr-image">
              <p class="payment-text">
                Selesaikan pembayaran terlebih dahulu agar pesanan bisa diproses.
              </p>
            </div>
          <?php endif; ?>

          <!-- Kiri: Order Items -->
          <div class="order-items">
            <?php if (empty($orderItems)): ?>
              <p>Belum ada item di order ini.</p>
            <?php else: ?>
              <?php foreach ($orderItems as $item): ?>
                <div class="order-item">
                  <div class="order-item-text">
                    <h3><?= $item['name']; ?></h3>
                    <p class="order-desc">
                      Harga: Rp <?= number_format((float) $item['price'], 0, ',', '.'); ?>
                    </p>
                    <span class="item-count"><?= $item['quantity']; ?> item(s)</span>
                  </div>

                  <img src="<?= $imagePath . $item['image']; ?>" alt="<?= $item['name']; ?>">
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Kanan: Order Summary -->
        <div class="order-summary">
          <h3>Order Details</h3>

          <?php if (!empty($order['address'])): ?>
            <p><?= $order['address']; ?></p>
          <?php endif; ?>

          <?php if (!empty($order['city'])): ?>
            <p><?= $order['city']; ?></p>
          <?php endif; ?>

          <?php if (!empty($order['postal_code'])): ?>
            <p><?= $order['postal_code']; ?></p>
          <?php endif; ?>

          <p>
            <strong>Contact</strong><br>
            Email: <?= $order['email'] ?? ''; ?><br>
            Phone: <?= $order['phone']; ?>
          </p>

          <p>
            <strong>Delivery Type</strong><br>
            <?= $order['delivery_type']; ?>
          </p>

          <p>
            <strong>Notes</strong><br>
            <?= $order['notes'] ?: '-'; ?>
          </p>

          <p>
            <strong>Total</strong><br>
            Rp <?= number_format((float) $order['total_price'], 0, ',', '.'); ?>
          </p>

          <div class="order-status <?= $statusClassMap[strtolower($order['status_type'])] ?? 'status-processed'; ?>">
            <?= $order['status_type']; ?>
          </div>
        </div>

      </div>
    <?php endif; ?>
  </section>

</body>
</html>
