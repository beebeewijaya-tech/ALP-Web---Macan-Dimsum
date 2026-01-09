<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $orders = [];

  $orderStmt = $conn->prepare('SELECT o.id, o.total_price, o.delivery_type, o.created, s.status_type FROM orders o JOIN statuses s ON s.id = o.status_id WHERE o.user_id = ? ORDER BY o.created DESC');
  $userID = $_SESSION['user_id'];
  $orderStmt->bind_param('i', $userID);
  $orderStmt->execute();
  $result = $orderStmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
  }
  $orderStmt->close();

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Pesanan Saya</h2>

    <?php if (empty($orders)): ?>
      <p style="text-align:center;">Belum ada pesanan.</p>
    <?php else: ?>
      <div class="order-items">
        <?php foreach ($orders as $order): ?>
          <div class="order-item">
            <div class="order-item-text">
              <h3>Order #<?= (int) $order['id']; ?></h3>
              <p class="order-desc">
                <?= $order['delivery_type']; ?> •
                <?= date('d M Y H:i', strtotime($order['created'])); ?>
              </p>
              <span class="item-count">Total: Rp <?= number_format((float) $order['total_price'], 0, ',', '.'); ?></span>
            </div>

            <div class="order-actions">
              <div class="order-status <?= strtolower($order['status_type']) === 'delivered' ? 'status-delivered' : 'status-processed'; ?>">
                <?= $order['status_type']; ?>
              </div>

              <a class="btn-primary" href="order_detail.php?id=<?= (int) $order['id']; ?>">Check</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</body>
</html>
