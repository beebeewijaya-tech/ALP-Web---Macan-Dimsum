<?php include __DIR__ . '/../partials/metadata.php'; ?>
<?php
  $orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  $order = null;
  $orderItems = [];
  $statusOptions = [];

  if ($result = $conn->query('SELECT id, status_type FROM statuses ORDER BY id')) {
    while ($row = $result->fetch_assoc()) {
      $statusOptions[] = $row;
    }
    $result->free();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_id'], $_POST['order_id'])) {
    $statusId = (int) $_POST['status_id'];
    $targetOrderId = (int) $_POST['order_id'];
    if ($statusId > 0 && $targetOrderId > 0) {
      $updateStmt = $conn->prepare('UPDATE orders SET status_id = ?, updated = NOW() WHERE id = ?');
      if ($updateStmt) {
        $updateStmt->bind_param('ii', $statusId, $targetOrderId);
        $updateStmt->execute();
        $updateStmt->close();
      }
    }
    header('Location: admin_order_detail.php?id=' . $targetOrderId);
    exit;
  }

  if ($orderId > 0) {
    $orderStmt = $conn->prepare('SELECT o.*, s.status_type, u.email FROM orders o JOIN statuses s ON s.id = o.status_id JOIN users u ON u.id = o.user_id WHERE o.id = ? LIMIT 1');
    $orderStmt->bind_param('i', $orderId);
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
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <!-- ORDER DETAIL SECTION -->
  <section class="section">
    <h2 class="section-title">Order Detail</h2>

    <?php if (!$order): ?>
      <p style="text-align:center;">Order tidak ditemukan.</p>
    <?php else: ?>
      <div class="order-detail-container">

        <!-- LEFT: ORDER ITEMS -->
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

        <!-- RIGHT: ORDER SUMMARY -->
        <div class="order-summary">
          <h3>Order Information</h3>

          <p><strong>Email</strong><br>
          <?= $order['email']; ?></p>

          <?php if (!empty($order['address'])): ?>
            <p><?= $order['address']; ?></p>
          <?php endif; ?>

          <?php if (!empty($order['city'])): ?>
            <p><?= $order['city']; ?></p>
          <?php endif; ?>

          <?php if (!empty($order['postal_code'])): ?>
            <p><?= $order['postal_code']; ?></p>
          <?php endif; ?>

          <p><strong>Delivery Type</strong><br>
          <?= $order['delivery_type']; ?></p>

          <p><strong>Notes</strong><br>
          <?= $order['notes'] ?: '-'; ?></p>

          <p><strong>Total</strong><br>
          Rp <?= number_format((float) $order['total_price'], 0, ',', '.'); ?></p>

          <form method="POST" class="status-form" style="margin-top:12px;">
            <input type="hidden" name="order_id" value="<?= (int) $order['id']; ?>">
            <label for="status_id"><strong>Update Status</strong></label>
            <select name="status_id" id="status_id">
              <?php foreach ($statusOptions as $status): ?>
                <option value="<?= (int) $status['id']; ?>" <?= (int) $status['id'] === (int) $order['status_id'] ? 'selected' : ''; ?>>
                  <?= $status['status_type']; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button class="btn-primary" type="submit">Update</button>
          </form>

        </div>

      </div>
    <?php endif; ?>
  </section>

</body>
</html>
