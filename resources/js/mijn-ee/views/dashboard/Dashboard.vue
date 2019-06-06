<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col>
        <Bar />
      </b-col>
    </b-row>

    <b-row class="mb-4">
      <b-col>
        <Sheet />
      </b-col>
    </b-row>

    <b-row>
      <b-col lg="6" class="mb-4">
        <h2 class="text-success text-center">
          {{ sumDebet | ee_valuta }}
          <i class="fa fa-arrow-up" aria-hidden="true"></i>
        </h2>
        <Doughnut v-if="debetData.length" :data="debetData" :labels="debetLabels" class="mb-3" />
        <div v-else class="text-center">Geen gegevens.</div>
      </b-col>

      <b-col lg="6" class="mb-4">
        <h2 class="text-danger text-center">
          {{ sumCredit | ee_valuta }}
          <i class="fa fa-arrow-down" aria-hidden="true"></i>
        </h2>
        <Doughnut v-if="creditData.length" :data="creditData" :labels="creditLabels" class="mb-3" />
        <div v-else class="text-center">Geen gegevens.</div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Bar from './bar'
import Sheet from './sheet'
import Doughnut from './doughnut'
import { SIDES } from '@/mijn-ee/globals/constants'

export default {
  name: 'Dashboard',
  components: { Bar, Sheet, Doughnut },
  computed: {
    ...mapState({
      debetLabels: state => state.charts.donutDebetLabels,
      creditLabels: state => state.charts.donutCreditLabels,
      debetData: state => state.charts.donutDebetData,
      creditData: state => state.charts.donutCreditData,
    }),
    sumDebet: function () { return _.sum(this.debetData) || 0 },
    sumCredit: function () { return _.sum(this.creditData) || 0 },
  },
}
</script>
