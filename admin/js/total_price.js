// admin/js/order_form.js

document.addEventListener('DOMContentLoaded', function() {
  const prodSel       = document.getElementById('product-select');
  const priceIn       = document.getElementById('price-per-unit');
  const qtyIn         = document.querySelector('input[name="quantity"]');
  const sizeIn        = document.querySelector('input[name="unit_size"]');
  const lineTotalSpan = document.getElementById('line-total');

  function refreshPricing() {
    const price = parseFloat(prodSel.selectedOptions[0].dataset.price) || 0;
    const qty   = parseFloat(qtyIn.value)   || 0;
    const size  = parseFloat(sizeIn.value)  || 0;

    priceIn.value = price.toFixed(2);

    const total = price * qty * size;
    lineTotalSpan.textContent = isNaN(total)
      ? '—'
      : total.toFixed(2);
  }

  prodSel .addEventListener('change',  refreshPricing);
  qtyIn   .addEventListener('input',   refreshPricing);
  sizeIn  .addEventListener('input',   refreshPricing);

  // initial fill
  prodSel.dispatchEvent(new Event('change'));
});
