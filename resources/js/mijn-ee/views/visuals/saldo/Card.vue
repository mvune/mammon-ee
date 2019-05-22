<template>

  <b-card>
    <b-row>
      <b-col sm="3">
        <h4 class="mb-1">Saldo</h4>
      </b-col>
      <b-col sm="5">
        <b-form-select class="d-inline-block time-select mb-2 float-right"
          v-model="year"
          :options="years"
          size="sm"
        ></b-form-select>
        <b-form-select class="d-inline-block time-select mb-2 mr-1 float-right"
          v-if="scope === scopes.MONTH"
          v-model="month"
          :options="months"
          size="sm"
        ></b-form-select>
      </b-col>
      <b-col sm="4">
        <b-button-toolbar class="float-right">
          <b-form-radio-group v-model="scope" buttons button-variant="outline-primary">
            <b-form-radio :value="scopes.MONTH">Maand</b-form-radio>
            <b-form-radio :value="scopes.YEAR">Jaar</b-form-radio>
          </b-form-radio-group>
        </b-button-toolbar>
      </b-col>
    </b-row>

    <div class="loading-container">
      <SaldoChart v-on:clicked="onChartClick"
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
.time-select {
  font-size: 0.875rem;
  width: 48%;
}
</style>

<script>
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import { MONTHS, SCOPES } from '@/mijn-ee/globals/constants.js'
import SaldoChart from './Chart'

export default {
  name: 'SaldoCard',
  components: { LoadingSpinner, SaldoChart },
  data () {
    return {
      chartData: [],
      scopedData: [],
      firstYear: null,
      lastYear: null,
      firstMonth: null,
      lastMonth: null,
      month: null,
      year: (new Date).getFullYear(),
      scope: SCOPES.YEAR,
      months: [],
      years: [
        { value: (new Date).getFullYear(), text: (new Date).getFullYear() },
      ],
      scopes: {},
      isBusy: false,
    }
  },
  created () {
    this.scopes = SCOPES;
    this.getBalances();
  },
  methods: {
    getBalances () {
      this.isBusy = true;

      axios.get('charts/balances')
        .then(response => {
          this.chartData = this.dataToChartData(response.data);
          this.scopeChartData();
          const firstItem = response.data[0];
          const lastItem = response.data[response.data.length - 1];
          this.firstYear = firstItem ? (new Date(firstItem.date).getFullYear()) : null;
          this.lastYear = lastItem ? (new Date(lastItem.date).getFullYear()) : null;
          this.firstMonth = firstItem ? (new Date(firstItem.date).getMonth()) : null;
          this.lastMonth = lastItem ? (new Date(lastItem.date).getMonth()) : null;
          this.populateYearSelect();
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
    populateYearSelect () {
      this.years = [];

      for (let y = this.firstYear; y <= this.lastYear; y++) {
        this.years.push({ value: y, text: y });
      }

      if (this.years.length > 0) {
        this.year = this.years[this.years.length - 1].value;
      }
    },
    populateMonthSelect () {
      if (this.scope === SCOPES.MONTH) {
        const fromMonth = (this.year === this.firstYear) ? this.firstMonth : undefined;
        const toMonth = (this.year === this.lastYear) ? this.lastMonth + 1 : undefined;

        this.months = MONTHS.slice(fromMonth, toMonth);

        if (fromMonth && (!this.month || fromMonth > this.month)) {
          this.month = fromMonth + 1;
        }
        if (toMonth && (!this.month || toMonth < this.month)) {
          this.month = toMonth;
        }
      } else {
        this.months = [];
      }
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
      this.populateMonthSelect();
      this.scopeChartData();
    },
    scope: function () {
      this.populateMonthSelect();
      this.scopeChartData();
    },
  },
}
</script>
