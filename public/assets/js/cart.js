const CART_KEY = '@cart';

const readCart = () => JSON.parse(localStorage.getItem(CART_KEY) || '{}');
const writeCart = (cart) => localStorage.setItem(CART_KEY, JSON.stringify(cart));

const renderState = (productId, qty) => {
  const addBtn = document.querySelector(`.add-cart-btn[data-product-id="${productId}"]`);
  const counter = document.querySelector(`.cart-counter[data-product-id="${productId}"]`);
  if (!addBtn || !counter) return;

  counter.querySelector('.qty-value').textContent = qty > 0 ? qty : 0;
  addBtn.classList.toggle('hidden', qty > 0);
  counter.classList.toggle('hidden', qty <= 0);
};

const cart = readCart();

document.querySelectorAll('.add-cart-btn').forEach((button) => {
  const productId = button.dataset.productId;
  const qty = Number(cart[productId] || 0);
  renderState(productId, qty);

  button.addEventListener('click', (event) => {
    event.preventDefault();
    cart[productId] = 1;
    writeCart(cart);
    renderState(productId, 1);
  });
});

document.querySelectorAll('.cart-counter').forEach((counter) => {
  const productId = counter.dataset.productId;
  const qty = Number(cart[productId] || 0);
  renderState(productId, qty);

  counter.addEventListener('click', (event) => {
    const action = event.target.dataset?.action;
    if (!action) return;
    const current = Number(cart[productId] || 0);
    const next = action === 'increment' ? current + 1 : current - 1;
    if (next <= 0) {
      delete cart[productId];
    } else {
      cart[productId] = next;
    }
    writeCart(cart);
    renderState(productId, next);
  });
});
