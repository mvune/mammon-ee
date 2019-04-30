<template>
  <table>
    <tr>
      <td>Rentedatum:</td>
      <td>{{ transaction.value_date | ee_date }}</td>
    </tr>
    <tr>
      <td>Verwerkingsdatum:</td>
      <td>{{ transaction.date | ee_date }}</td>
    </tr>
    <tr v-if="transaction.counterparty_iban">
      <td>Tegenrekening/IBAN:</td>
      <td>{{ transaction.counterparty_iban }}</td>
    </tr>
    <tr v-if="transaction.description_1">
      <td>Omschrijving:</td>
      <td>{{ transaction.description_1 }}</td>
    </tr>
    <tr>
      <td>Soort:</td>
      <td>{{ transaction.code }}</td>
    </tr>
    <tr v-if="transaction.reference">
      <td>Transactiereferentie:</td>
      <td>{{ transaction.reference }}</td>
    </tr>
    <tr v-if="transaction.payment_code">
      <td>Betalingskenmerk:</td>
      <td>{{ transaction.payment_code }}</td>
    </tr>
  </table>
</template>

<style lang="scss" scoped>
td {
  border: 0;
  padding: 0 0 0 1.5rem;
}
</style>

<script>
export default {
  name: 'TransactionDetails',
  props: ['transaction'],
}
</script>
