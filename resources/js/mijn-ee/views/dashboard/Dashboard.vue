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

    <b-row class="mb-4">
      <b-col v-if="debetData.length" lg="6">
        <Doughnut :side="sides.debet" :data="debetData" :labels="debetLabels" />
      </b-col>

      <b-col v-if="creditData.length" lg="6">
        <Doughnut :side="sides.credit" :data="creditData" :labels="creditLabels" />
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
  data () {
    return {
      sides: SIDES,
    }
  },
  computed: {
    ...mapState({
      debetLabels: state => state.charts.donutDebetLabels,
      creditLabels: state => state.charts.donutCreditLabels,
      debetData: state => state.charts.donutDebetData,
      creditData: state => state.charts.donutCreditData,
    }),
  },
}
</script>
