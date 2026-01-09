const CART_KEY = '@cart';
const productCatalog = window.PRODUCT_CATALOG || {};
const imageBasePath = window.IMAGE_BASE_PATH || '';

const cartContainer = document.getElementById('cart-items');
const totalPrice = document.getElementById('total-price');
const totalPriceValue = document.getElementById('total-price-value');
const cartItemsValue = document.getElementById('cart-items-value');
const emptyState = document.getElementById('cart-empty');
const currencyFormatter = new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  minimumFractionDigits: 0
});

const readCart = () => JSON.parse(localStorage.getItem(CART_KEY) || '{}');
const writeCart = (cart) => localStorage.setItem(CART_KEY, JSON.stringify(cart));

const toggleEmptyState = (showEmpty) => {
  if (!emptyState) return;
  emptyState.style.display = showEmpty ? 'block' : 'none';
};

const renderCartItems = () => {
  if (!cartContainer) return;

  const cart = readCart();
  const entries = Object.entries(cart);

  cartContainer.innerHTML = '';

  if (entries.length === 0) {
    toggleEmptyState(true);
     if (totalPrice) totalPrice.innerText = 'Total Harga: Rp 0';
     if (totalPriceValue) totalPriceValue.value = '';
     if (cartItemsValue) cartItemsValue.value = '{}';
    return;
  }

  toggleEmptyState(false);
  let tp = 0

  entries.forEach(([productId, quantity]) => {
    const product = productCatalog[productId];
    if (!product) return;

    const itemElement = document.createElement('div');
    itemElement.className = 'cart-item';
    itemElement.innerHTML = `
      <img src="${imageBasePath}${product.image}" alt="${product.name}" class="cart-item-img">
      <div class="cart-item-info">
        <h3>${product.name}</h3>
        <p class="price">${currencyFormatter.format(product.price)}</p>
        <p class="totalPrice">Subtotal: ${currencyFormatter.format(product.price * quantity)}</p>
      </div>
      <div class="qty-control">
        <button type="button" class="qty-btn" data-action="decrement" data-product-id="${productId}">-</button>
        <span class="qty-value">${quantity}</span>
        <button type="button" class="qty-btn" data-action="increment" data-product-id="${productId}">+</button>
      </div>
    `;
    tp += (product.price * quantity)
    cartContainer.appendChild(itemElement);
  });

  totalPrice.innerText = `Total Harga: ${currencyFormatter.format(tp)}`;
  totalPriceValue.value = tp;
  if (cartItemsValue) {
    cartItemsValue.value = JSON.stringify(cart);
  }
};

const updateQuantity = (productId, delta) => {
  const cart = readCart();
  const current = Number(cart[productId] || 0);
  const next = current + delta;
  if (next <= 0) {
    delete cart[productId];
  } else {
    cart[productId] = next;
  }
  writeCart(cart);
  renderCartItems();
};

if (cartContainer) {
  cartContainer.addEventListener('click', (event) => {
    const button = event.target.closest('button[data-action]');
    if (!button) return;
    const { action, productId } = button.dataset;
    if (!productId) return;
    if (action === 'increment') {
      updateQuantity(productId, 1);
    } else if (action === 'decrement') {
      updateQuantity(productId, -1);
    }
  });
}

renderCartItems();
