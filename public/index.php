<?php
?>

<?php include __DIR__ . '/partials/metadata.php'; ?>
<body>

  <?php include __DIR__ . '/partials/header.php'; ?>

  <!-- Menu Andalan -->
  <section class="section">
    <h2 class="section-title">Menu Andalan Kami</h2>

    <div class="product-grid">
      <div class="product-card">
        <img src="<?= $imagePath ?>dimsum1.png" alt="Dimsum Mentai">
        <h3>Dimsum Mentai</h3>
        <p class="price">Rp 22.000</p>
        <button class="btn-primary">Add to Cart</button>
      </div>

      <div class="product-card">
        <img src="<?= $imagePath ?>dimsum2.png" alt="Dimsum Isian Keju">
        <h3>Dimsum Isian Keju</h3>
        <p class="price">Rp 22.000</p>
        <button class="btn-primary">Add to Cart</button>
      </div>

      <div class="product-card">
        <img src="<?= $imagePath ?>dimsum3.png" alt="Dimsum Kuah Creamy">
        <h3>Dimsum Kuah Creamy</h3>
        <p class="price">Rp 16.000</p>
        <button class="btn-primary">Add to Cart</button>
      </div>
    </div>
  </section>

  <!-- Menu Lainnya -->
  <section class="section">
    <h2 class="section-title">Menu Lainnya</h2>

    <div class="product-grid">
      <div class="product-card">
        <img src="<?= $imagePath ?>dimsum4.png" alt="Dimsum Mentai Party Size">
        <h3>Dimsum Mentai Party Size</h3>
        <p class="price">Rp 58.000</p>
        <button class="btn-primary">Add to Cart</button>
      </div>

      <div class="product-card">
        <img src="<?= $imagePath ?>dimsum5.png" alt="Dimsum Original">
        <h3>Dimsum Original</h3>
        <p class="price">Rp 18.000</p>
        <button class="btn-primary">Add to Cart</button>
      </div>

      <div class="product-card">
        <img src="<?= $imagePath ?>dimsum6.png" alt="Dimsum Mentai Isian Keju">
        <h3>Dimsum Mentai Isian Keju</h3>
        <p class="price">Rp 26.000</p>
        <button class="btn-primary">Add to Cart</button>
      </div>
    </div>
  </section>

</body>
</html>
