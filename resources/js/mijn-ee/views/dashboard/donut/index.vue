<template>

  <b-row>
    <b-col lg="6">
      <LoadingContainer :loading="isBusy">
        <Doughnut :side="sides.debet" :data="debetData" :labels="debetLabels" />
      </LoadingContainer>
    </b-col>

    <b-col lg="6">
      <LoadingContainer :loading="isBusy">
        <Doughnut :side="sides.credit" :data="creditData" :labels="creditLabels" />
      </LoadingContainer>
    </b-col>
  </b-row>
  
</template>

<script>
import { mapState } from 'vuex'
import { switchMap, tap } from 'rxjs/operators'
import { SIDES } from '@/mijn-ee/globals/constants'
import LoadingContainer from '@/mijn-ee/partials/loading/Container'
import * as ChartService from '@/mijn-ee/services/ChartService'
import Doughnut from './Doughnut'

export default {
  name: 'Donut',
  components: { Doughnut, LoadingContainer },
  data () {
    return {
      dataSubscription: null,
      isBusy: false,
      sides: SIDES,
    }
  },
  computed: {
    ...mapState({
      debetData: state => state.charts.donutDebetData,
      creditData: state => state.charts.donutCreditData,
      debetLabels: state => state.charts.donutDebetLabels,
      creditLabels: state => state.charts.donutCreditLabels,
      filters: state => state.filters.filters,
    }),
  },
  created () {
    this.getData();
  },
  methods: {
    getData () {
      this.isBusy = true;

      ChartService.getDonutData(this.filters).subscribe(
        this.handleResponse,
        this.ee_errorHandler,
        () => this.isBusy = false
      );
    },
    handleResponse (res) {
      this.$store.dispatch('setDonutData', res.data);
    },
  },
  watch: {
    filters (newVal, oldVal) {
      const isInit = _.isEmpty(oldVal);

      if (!isInit) {
        this.getData();
      }
    },
  },
}
</script>
