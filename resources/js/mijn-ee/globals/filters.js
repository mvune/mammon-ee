const eurFormatter = new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' })

Vue.filter('valuta', function (value) {
  return eurFormatter.format(value);
})
