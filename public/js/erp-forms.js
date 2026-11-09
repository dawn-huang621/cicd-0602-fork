'use strict';

const money = new Intl.NumberFormat('zh-TW', { maximumFractionDigits: 0 });
document.querySelectorAll('[data-erp-form]').forEach((form) => {
  form.addEventListener('submit', () => {
    const button = form.querySelector('[data-save]');
    if (button) {
      button.dataset.originalText = button.textContent;
      button.disabled = true;
      button.textContent = '正在儲存…';
    }
  });
});
window.addEventListener('pageshow', () => {
  document.querySelectorAll('[data-save]').forEach((button) => {
    button.disabled = false;
    if (button.dataset.originalText) button.textContent = button.dataset.originalText;
  });
});

const items = document.querySelector('#items');
if (items) {
  const template = document.querySelector('#item-row-template');
  let nextIndex = Math.max(-1, ...Array.from(items.querySelectorAll('[data-index]'), (row) => Number(row.dataset.index))) + 1;
  function updateOrder() {
    const rows = Array.from(items.querySelectorAll('.item-row'));
    let total = 0;
    rows.forEach((row, index) => {
      const select = row.querySelector('.product-select');
      const option = select.selectedOptions[0];
      const historical = Array.from(document.querySelectorAll('[data-original-product]')).find((item) => item.dataset.originalProduct === select.value);
      const price = Number(historical?.dataset.originalPrice ?? option?.dataset.price ?? 0);
      const quantity = Number(row.querySelector('.quantity-input').value) || 0;
      row.querySelector('.unit-price').textContent = `NT$ ${money.format(price)}`;
      row.querySelector('.subtotal').textContent = `NT$ ${money.format(price * quantity)}`;
      row.querySelector('.remove-item').disabled = rows.length === 1;
      select.setAttribute('aria-label', `第 ${index + 1} 筆商品`);
      row.querySelector('.quantity-input').setAttribute('aria-label', `第 ${index + 1} 筆數量`);
      total += price * quantity;
    });
    document.querySelectorAll('[data-order-total]').forEach((node) => { node.textContent = `NT$ ${money.format(total)}`; });
    const count = document.querySelector('[data-item-count]');
    if (count) count.textContent = `${rows.length} 項商品`;
  }
  document.querySelector('#add-item')?.addEventListener('click', () => {
    const row = template.content.firstElementChild.cloneNode(true);
    row.dataset.index = nextIndex;
    row.querySelector('.product-select').name = `products[${nextIndex}][product_id]`;
    row.querySelector('.quantity-input').name = `products[${nextIndex}][quantities]`;
    nextIndex++;
    items.appendChild(row);
    row.querySelector('select').focus();
    updateOrder();
  });
  items.addEventListener('input', updateOrder);
  items.addEventListener('change', updateOrder);
  items.addEventListener('click', (event) => {
    const button = event.target.closest('.remove-item');
    if (!button || button.disabled) return;
    const focusTarget = button.closest('tr').previousElementSibling || button.closest('tr').nextElementSibling;
    button.closest('tr').remove();
    focusTarget?.querySelector('select').focus();
    updateOrder();
  });
  updateOrder();
}

const stockForm = document.querySelector('[data-stock-form]');
if (stockForm) {
  const updateStock = () => {
    const product = stockForm.querySelector('#product_id');
    const current = Number(product?.selectedOptions[0]?.dataset.stock ?? stockForm.dataset.currentStock ?? 0);
    const adjustment = stockForm.querySelector('#target_quantity');
    const quantity = Number(stockForm.querySelector('#quantity')?.value || 0);
    const direction = stockForm.querySelector('#type')?.value;
    const balance = adjustment ? Number(adjustment.value || 0) : current + (direction === 'out' ? -quantity : direction === 'in' ? quantity : 0);
    stockForm.querySelectorAll('[data-current-stock]').forEach((node) => { node.textContent = money.format(current); });
    stockForm.querySelectorAll('[data-stock-balance]').forEach((node) => { node.textContent = money.format(balance); });
    const input = stockForm.querySelector('#quantity');
    if (input) input.setCustomValidity(direction === 'out' && quantity > current ? '出庫數量不可超過目前庫存。' : '');
  };
  stockForm.addEventListener('input', updateStock);
  stockForm.addEventListener('change', updateStock);
  updateStock();
}
