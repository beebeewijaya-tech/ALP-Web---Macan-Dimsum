<?php include __DIR__ . '/../partials/metadata.php'; ?>
<?php
  $statusOptions = [];
  $orders = [];
  $statusClassMap = [
    'unpaid' => 'status-unpaid',
    'paid' => 'status-paid',
    'processed' => 'status-processed',
    'delivered' => 'status-delivered'
  ];

  if ($result = $conn->query('SELECT id, status_type FROM statuses ORDER BY id')) {
    while ($row = $result->fetch_assoc()) {
      $statusOptions[] = $row;
    }
    $result->free();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status_id'])) {
    $orderId = (int) $_POST['order_id'];
    $statusId = (int) $_POST['status_id'];
    if ($orderId > 0 && $statusId > 0) {
      $updateStmt = $conn->prepare('UPDATE orders SET status_id = ?, updated = NOW() WHERE id = ?');
      if ($updateStmt) {
        $updateStmt->bind_param('ii', $statusId, $orderId);
        $updateStmt->execute();
        $updateStmt->close();
      }
    }
    header('Location: index.php');
    exit;
  }

  $query = '
    SELECT o.id, o.delivery_type, o.total_price, o.created, s.status_type, s.id AS status_id, u.email,
      COALESCE(SUM(oi.quantity), 0) AS item_count
    FROM orders o
    JOIN statuses s ON s.id = o.status_id
    LEFT JOIN users u ON u.id = o.user_id
    LEFT JOIN order_items oi ON oi.order_id = o.id
    GROUP BY o.id, s.status_type, s.id, u.email
    ORDER BY o.created DESC
  ';
  if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
      $orders[] = $row;
    }
    $result->free();
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/../partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Incoming Orders</h2>

    <?php if (empty($orders)): ?>
      <p style="text-align:center;">Belum ada pesanan.</p>
    <?php else: ?>
      <div class="admin-order-list">
        <?php foreach ($orders as $order): ?>
          <div class="admin-order-card">

            <div class="order-main-info">
              <h3><?= $order['email'] ?> - Order  #<?= $order['id'] ?></h3>
              <p class="order-desc"><?= (int) $order['item_count']; ?> Items • <?= $order['delivery_type']; ?> • <?= $order['created']; ?></p>
              <span class="item-count">Total: Rp <?= number_format((float) $order['total_price'], 0, ',', '.'); ?></span>
            </div>

            <div class="order-actions">
              <span class="order-status <?= $statusClassMap[strtolower($order['status_type'])] ?? 'status-processed'; ?>">
                <?= $order['status_type']; ?>
              </span>
              <form method="POST" class="status-form">
                <input type="hidden" name="order_id" value="<?= (int) $order['id']; ?>">
                <select name="status_id">
                  <?php foreach ($statusOptions as $status): ?>
                    <option value="<?= (int) $status['id']; ?>" <?= (int) $status['id'] === (int) $order['status_id'] ? 'selected' : ''; ?>>
                      <?= $status['status_type']; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button class="btn-primary" type="submit">Update</button>
              </form>
              <a href="admin_order_detail.php?id=<?= (int) $order['id']; ?>">
                <button class="btn-primary" type="button">View</button>
              </a>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

</body>
</html>
