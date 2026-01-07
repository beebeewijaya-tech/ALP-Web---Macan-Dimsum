<?php
?>

<?php include __DIR__ . '/partials/metadata.php'; ?>
<body>

  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Order details</h2>

    <div class="order-detail-container">

      <!-- Kiri: Order Items -->
      <div class="order-items">

        <div class="order-item">
          <div class="order-item-text">
            <h3>Dimsum Mentai</h3>
            <p class="order-desc">
              Dimsum ayam yang gurih dan juicy, disajikan dengan saus mentai ala Jepang dengan real tobiko.
            </p>
            <span class="item-count">2 items</span>
          </div>

          <img src="<?= $imagePath ?>dimsum1.png" alt="Dimsum Mentai">
        </div>

        <div class="order-item">
          <div class="order-item-text">
            <h3>Dimsum Kuah Creamy</h3>
            <p class="order-desc">
             Dimsum ayam dengan tekstur lembut dan rasa gurih khas dipadukan dengan kuah creamy yang hangat.
            </p>
            <span class="item-count">1 item</span>
          </div>

          <img src="<?= $imagePath ?>dimsum3.png" alt="Dimsum Kuah Creamy">
        </div>

      </div>

      <!-- Kanan: Order Summary -->
      <div class="order-summary">
        <h3>Order Details</h3>

        <p>
          <strong>Address</strong><br>
          Jl. Halim Perdana No. 11, Bangkalan
        </p>

        <p>
          <strong>Contact</strong><br>
          Email: test@gmail.com<br>
          Phone: +62 811 333 44
        </p>

        <p>
          <strong>Delivery Type</strong><br>
          Delivery
        </p>

        <p>
          <strong>Notes</strong><br>
          untuk dimsum kuah tolong chili oilnya dipisah ya.
        </p>

        <div class="order-status status-processed">
          Processed
        </div>
      </div>

    </div>
  </section>

</body>
</html>
