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
      <td>{{ transaction.code.label }}</td>
    </tr>
    <tr v-if="transaction.reference">
      <td>Transactiereferentie:</td>
      <td>{{ transaction.reference }}</td>
    </tr>
    <tr v-if="transaction.payment_code">
      <td>Betalingskenmerk:</td>
      <td>{{ transaction.payment_code }}</td>
    </tr>
    <tr v-if="transaction.category">
      <td>Categorie:</td>
      <td><i aria-hidden="true" class="icon-tag icons"></i> {{ transaction.category.name }}</td>
    </tr>
  </table>
</template>

<style lang="scss" scoped>
@import '@/mijn-ee/variables.scss';

td {
  border: 0;
  padding: 0 0 0 1.5rem;
}

.icons {
  color: $theme-tertiary;
}
</style>

<script>
export default {
  name: 'TransactionsDetails',
  props: ['transaction'],
}
</script>
