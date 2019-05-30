<template>

  <b-card>
    <div class="loading-container">
      <BarChart v-on:clicked="onChartClick"
        height="160"
        :data="scopedData"
        :month="month"
        :year="year"
        :scope="scope"
      />
      <LoadingSpinner :loading="isBusy" />
    </div>
  </b-card>

</template>

<script>
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import { SCOPES } from '@/mijn-ee/globals/constants'
import BarChart from './Chart'

export default {
  name: 'Bar',
  components: { LoadingSpinner, BarChart },
  data () {
    return {
      chartData: [],
      scopedData: [],
      isBusy: false,
    }
  },
  computed: {
    scope: {
      get: function () { return this.$store.state.scope },
      set: function (value) { this.$store.dispatch('setScope', value) }
    },
    month: {
      get: function () { return this.$store.state.month },
      set: function (value) { this.$store.dispatch('setMonth', value) }
    },
    year: {
      get: function () { return this.$store.state.year },
      set: function (value) { this.$store.dispatch('setYear', value) }
    },
  },
  created () {
    this.getBalances();
  },
  methods: {
    getBalances () {
      this.isBusy = true;

      axios.get('charts/balances')
        .then(response => {
          this.chartData = this.dataToChartData(response.data);
          this.scopeChartData();
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    dataToChartData (data) {
      const chartData = [];

      for (let item of data) {
        chartData.push({
          x: item.date,
          y: item.balance,
        });
      }

      return chartData;
    },
    scopeChartData () {
      const [fromDate, toDate] = this.getBoundaryDates();
      const fromDateExcl = new Date(fromDate);
      const toDateExcl = new Date(toDate);
      fromDateExcl.setDate(fromDateExcl.getDate() - 1);
      toDateExcl.setDate(toDateExcl.getDate() + 1);

      let scopedData = this.chartData.filter(item => {
        const d = new Date(item.x);
        d.setHours(6);
        
        return d > fromDateExcl && d < toDateExcl;
      });

      const cursor = new Date(fromDate);
      const betweens = [];
      const lastDate = new Date(this.chartData[this.chartData.length - 1].x);
      let previousBalance;

      const firstItemIndex = this.chartData.findIndex(item => {
        return scopedData[0] ? item.x === scopedData[0].x : false;
      });
      
      if (firstItemIndex > 0) {
        previousBalance = this.chartData[firstItemIndex - 1].y;
      }

      while (cursor < toDateExcl) {
        let rememberItem = scopedData.filter(item => {
          return (new Date(item.x)).toISOString().substring(0, 10) === cursor.toISOString().substring(0, 10);
        });

        if (rememberItem.length > 0) {
          previousBalance = rememberItem[0].y;
        } else {
          if (cursor < lastDate) {
            betweens.push({x: cursor.toISOString().substring(0, 10), y: previousBalance});
          } else {
            betweens.push({x: cursor.toISOString().substring(0, 10), y: undefined});
          }
        }

        cursor.setDate(cursor.getDate() + 1);
      }

      scopedData.push(...betweens);
      scopedData = this.sortByDate(scopedData);
      this.scopedData = scopedData;
    },
    getBoundaryDates () {
      const fromDate = new Date(this.year.toString());
      const toDate = new Date(this.year.toString());
      fromDate.setHours(6);
      toDate.setHours(6);

      if (this.scope === SCOPES.YEAR) {
        toDate.setFullYear(this.year + 1);
        toDate.setDate(0); // Set to last day of previous month.
      }
      
      if (this.scope === SCOPES.MONTH) {
        fromDate.setMonth(this.month - 1);
        toDate.setMonth(this.month);
        toDate.setDate(0); // Set to last day of previous month.
      }

      return [fromDate, toDate];
    },
    sortByDate (scopedData) {
      return scopedData.sort((first, second) => {
        const fd = new Date(first.x);
        const sd = new Date(second.x);

        if (fd < sd) return -1;
        if (fd > sd) return 1;
        if (fd.getTime() === sd.getTime()) return 0;
      });
    },
    onChartClick (elements) {
      if (elements.length > 0 && this.scope === SCOPES.YEAR) {
        this.month = (new Date(elements[0]._chart.data.datasets[0].data[elements[0]._index].x)).getMonth() + 1;
        this.scope = SCOPES.MONTH;
      }
    },
  },
  watch: {
    month: function () {
      this.scopeChartData();
    },
    year: function () {
      this.scopeChartData();
    },
    scope: function () {
      this.scopeChartData();
    },
  },
}
</script>
