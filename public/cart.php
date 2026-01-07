<?php
?>

<?php include __DIR__ . '/partials/metadata.php'; ?>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="section">
    <h2 class="section-title">Cart</h2>

    <div class="cart-container">

      <!-- Kiri: Cart Items -->
      <div class="cart-items">

        <div class="cart-item">
          <img src="<?= $imagePath ?>dimsum1.png" alt="Dimsum Mentai" class="cart-item-img">

          <div class="cart-item-info">
            <h3>Dimsum Mentai</h3>
            <p class="cart-desc">
              Dimsum ayam yang gurih dan juicy, disajikan dengan saus mentai ala Jepang dengan real tobiko.
            </p>
            <p class="price">Rp 22.000</p>
          </div>

          <div class="qty-control">
            <button>-</button>
            <span>2</span>
            <button>+</button>
          </div>
        </div>

        <div class="cart-item">
          <img src="<?= $imagePath ?>dimsum3.png" alt="Dimsum Ayam" class="cart-item-img">

          <div class="cart-item-info">
            <h3>Dimsum Kuah Creamy</h3>
            <p class="cart-desc">
              Dimsum ayam dengan tekstur lembut dan rasa gurih khas dipadukan dengan kuah creamy yang hangat.
            </p>
            <p class="price">Rp 16.000</p>
          </div>

          <div class="qty-control">
            <button>-</button>
            <span>1</span>
            <button>+</button>
          </div>
        </div>

      </div>

      <!-- Kanan: Order Details -->
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
        <textarea placeholder="add notes here..."></textarea>

        <button class="btn-primary" type="button">
          Checkout
        </button>
      </div>

    </div>
  </section>

</body>
</html>
