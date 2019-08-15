<template>
  <div class="animated fadeIn">
    <b-tabs class="bare-tabs">
      <b-tab>
        <template slot="title">
          <i class="icon-pie-chart icons" aria-label="Donut"></i>
        </template>

        <b-row>
          <b-col lg="6">
            <Doughnut :side="sides.debet" :data="debetData" :labels="debetLabels" />
          </b-col>

          <b-col lg="6">
            <Doughnut :side="sides.credit" :data="creditData" :labels="creditLabels" />
          </b-col>
        </b-row>
      </b-tab>

      <b-tab>
        <template slot="title">
          <i class="icon-list icons" aria-label="Sheet"></i>
        </template>

        <b-row>
          <b-col>
            <Sheet class="mb-3" />
          </b-col>
        </b-row>
      </b-tab>

      <b-tab>
        <template slot="title">
          <i class="icon-chart icons" aria-label="Saldo"></i>
        </template>

        <b-row>
          <b-col>
            <Bar />
          </b-col>
        </b-row>
      </b-tab>
    </b-tabs>
  </div>
</template>

<style lang="scss" scoped>
i.icons {
  font-size: 1.2rem;
}
</style>

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
