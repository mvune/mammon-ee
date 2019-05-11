<template>
  
  <b-row>
    <b-col lg="11">
      <SaldoCard />
    </b-col>
  </b-row>

</template>

<script>
import SaldoCard from './cards/Saldo'

export default {
  name: 'DashboardIndex',
  components: { SaldoCard },
}
</script>
