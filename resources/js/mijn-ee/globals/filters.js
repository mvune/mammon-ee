const eurFormatter = new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' });
const nbsp = String.fromCharCode(160);

Vue.filter('ee_valuta', function (value, plusSign = false, minSign = true, symbol = true) {
  let output = eurFormatter.format(value);

  if (plusSign && value > 0) {
    output = output.replace(nbsp, nbsp + '+');
  }
  if (!minSign) {
    output = output.replace('-', '');
  }
  if (!symbol) {
    output = output.replace('€', '');
  }

  return output;
})

Vue.filter('ee_date', function (value) {
  return value ? value.split('-').reverse().join('-') : value;
})

Vue.filter('ee_iban', function (value) {
  return value ? value.match(/.{1,4}/g).join(nbsp) : value;
})
