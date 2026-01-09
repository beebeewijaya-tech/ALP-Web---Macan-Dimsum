<?php include __DIR__ . '/partials/metadata.php'; ?>
<?php
  $orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
  $userID = $_SESSION['user_id'];
  $order = null;

  if ($orderId > 0) {
    $stmt = $conn->prepare('SELECT o.*, s.status_type FROM orders o JOIN statuses s ON s.id = o.status_id WHERE o.id = ? AND o.user_id = ? LIMIT 1');
    $stmt->bind_param('ii', $orderId, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result ? $result->fetch_assoc() : null;
    $stmt->close();
  }

  if (!$order || strtolower($order['status_type']) !== 'unpaid') {
    $conn->close();
    header('Location: order.php');
    exit;
  }

  $conn->close();
?>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Payment</h2>

    <div class="payment-container">

      <div class="payment-card">
        <h3>Scan QR to Pay</h3>

        <img src="<?= $imagePath ?>qris.png" alt="QR Payment" class="qr-image">

        <p class="payment-text">
          Silakan lakukan pembayaran melalui QRIS.<br>
          Setelah pembayaran berhasil, klik tombol di bawah.
        </p>

        <a class="btn-primary" href="order_detail.php?id=<?= $orderId; ?>">Check Order Status</a>
      </div>

    </div>
  </section>
</body>
</html>
