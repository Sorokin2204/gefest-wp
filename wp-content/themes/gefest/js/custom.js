console.log('custom work');
const cart_value = document.getElementById('cart_value');
const cart_step = document.getElementById('cart_step');
const btn_plus_step = document.getElementById('btn_plus_step');
const btn_minus_step = document.getElementById('btn_minus_step');
const cart_price = document.getElementById('cart_price');
const cart_show_total = document.getElementById('cart_show_total');
const cart_show_pallet = document.getElementById('cart_show_pallet');
if (cart_value?.value && cart_price?.value && cart_step?.value) {
  cart_show_pallet.innerHTML = (parseFloat(cart_step?.value) * parseFloat(cart_value?.value)).toFixed(2) + ' м²';
  cart_show_total.innerHTML = (parseFloat(cart_step?.value) * parseFloat(cart_price?.value)).toFixed(3) + ' р.';
  btn_plus_step.addEventListener('click', () => {
    const currentVal = parseInt(cart_step.value) + 1;
    cart_step.setAttribute('value', currentVal);
    cart_show_pallet.innerHTML = (currentVal * parseFloat(cart_value?.value)).toFixed(2) + ' м²';
    cart_show_total.innerHTML = (currentVal * parseFloat(cart_price?.value)).toFixed(3) + ' р.';
  });
  btn_minus_step.addEventListener('click', () => {
    if (parseInt(cart_step.value) !== 1) {
      const currentVal = parseInt(cart_step.value) - 1;
      cart_step.setAttribute('value', currentVal);
      cart_show_pallet.innerHTML = (currentVal * parseFloat(cart_value?.value)).toFixed(2) + ' м²';
      cart_show_total.innerHTML = (currentVal * parseFloat(cart_price?.value)).toFixed(3) + ' р.';
    }
  });
}
