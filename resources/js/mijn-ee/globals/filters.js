const eurFormatter = new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' })

Vue.filter('ee_valuta', function (value) {
  let output = eurFormatter.format(value);

  if (value > 0) {
    let nbsp = String.fromCharCode(160);
    output = output.replace(nbsp, nbsp + '+');
  }

  return output;
})

Vue.filter('ee_date', function (value) {
  return value ? value.split('-').reverse().join('-') : value;
})

Vue.filter('ee_iban', function (value) {
  return value ? value.match(/.{1,4}/g).join(' ') : value;
})
