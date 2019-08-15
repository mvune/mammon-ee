<template>

  <b-card>
    <b-row>
      <b-col sm="3">
        <h4 class="text-primary mt-1">Saldo</h4>
      </b-col>
      <b-col sm="9">
        <DateScopeFilter />
      </b-col>
    </b-row>

    <div class="loading-container">
      <BarChart
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

<style lang="scss" scoped>
.card-body {
  padding: 0.75rem;
}
</style>

<script>
import { mapState } from 'vuex'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import { SCOPES } from '@/mijn-ee/globals/constants'
import BarChart from './Chart'
import DateScopeFilter from '@/mijn-ee/partials/filters/DateScopeFilter'

export default {
  name: 'Bar',
  components: { LoadingSpinner, BarChart, DateScopeFilter },
  data () {
    return {
      chartData: {},
      scopedData: {},
      isBusy: false,
    }
  },
  computed: {
    ...mapState({
      month: state => state.filters.month,
      year: state => state.filters.year,
      scope: state => state.filters.scope,
    }),
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
      const chartData = {};

      for (let accountId in data) {
        chartData[accountId] = [];

        for (let item of data[accountId]) {
          chartData[accountId].push({
            x: item.date,
            y: item.balance,
          });
        }
      }

      return chartData;
    },
    scopeChartData () {
      const [fromDate, toDate] = this.getBoundaryDates();
      const fromDateExcl = new Date(fromDate);
      const toDateExcl = new Date(toDate);
      fromDateExcl.setDate(fromDateExcl.getDate() - 1);
      toDateExcl.setDate(toDateExcl.getDate() + 1);
      let scopedDatas = {};

      for (let accountId in this.chartData) {
        let scopedData = this.chartData[accountId].filter(item => {
          const d = new Date(item.x);
          d.setHours(6);
          
          return d > fromDateExcl && d < toDateExcl;
        });

        const cursor = new Date(fromDate);
        const betweens = [];
        const lastDate = this.chartData[accountId].length > 0
          ? new Date(this.chartData[accountId][this.chartData[accountId].length - 1].x)
          : null;
        let previousBalance;

        const firstItemIndex = this.chartData[accountId].findIndex(item => {
          return scopedData[0] ? item.x === scopedData[0].x : false;
        });
        
        if (firstItemIndex > 0) {
          previousBalance = this.chartData[accountId][firstItemIndex - 1].y;
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
        scopedDatas[accountId] = scopedData;
      }

      this.scopedData = scopedDatas;
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
