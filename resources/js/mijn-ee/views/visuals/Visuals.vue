<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col lg="11">
        <SaldoCard />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import SaldoCard from './saldo/Card'

export default {
  name: 'Visuals',
  components: { SaldoCard },
}
</script>
