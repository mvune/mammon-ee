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
    <SaldoChart v-on:clicked="onChartClick" height="180" :data="balances" :month="month" :year="year" :scope="scope" />
  </b-card>

</template>

<style lang="scss" scoped>
.time-select {
  width: 48%;
}
</style>

<script>
import SaldoChart from '../charts/Saldo'
import { SCOPES } from '../filters.js'

export default {
  name: 'SaldoCard',
  components: { SaldoChart },
  data () {
    return {
      balances: [],
      month: 1,
      year: (new Date).getFullYear(),
      scope: SCOPES.YEAR,
      months: [
        { value: '1', text: 'januari' },
        { value: '2', text: 'februari' },
        { value: '3', text: 'maart' },
        { value: '4', text: 'april' },
        { value: '5', text: 'mei' },
        { value: '6', text: 'juni' },
        { value: '7', text: 'juli' },
        { value: '8', text: 'augustus' },
        { value: '9', text: 'september' },
        { value: '10', text: 'oktober' },
        { value: '11', text: 'november' },
        { value: '12', text: 'december' },
      ],
      years: [
        { value: (new Date).getFullYear(), text: (new Date).getFullYear() },
      ],
      scopes: {},
    }
  },
  created () {
    this.scopes = SCOPES;
    this.getBalances();
  },
  methods: {
    getBalances () {
      axios.get('charts/balances')
        .then(response => {
          this.balances = response.data;
          const firstItem = response.data[0];
          const lastItem = response.data[response.data.length - 1];
          const fromYear = firstItem ? (new Date(firstItem.date)).getFullYear() : null;
          const toYear = lastItem ? (new Date(lastItem.date)).getFullYear() : null;
          this.populateYearSelect(fromYear, toYear);
        })
        .catch(this.ee_errorHandler);
    },
    populateYearSelect (fromYear, toYear) {
      if (fromYear > toYear) {
        [fromYear, toYear] = [toYear, fromYear];
      }

      this.years = [];

      for (let y = fromYear; y <= toYear; y++) {
        this.years.push({ value: y, text: y });
      }

      if (this.years.length > 0) {
        this.year = this.years[this.years.length - 1].value;
      }
    },
    onChartClick (elements) {
      if (elements.length > 0 && this.scope === this.scopes.YEAR) {
        this.month = (new Date(elements[0]._chart.data.datasets[0].data[elements[0]._index].x)).getMonth() + 1;
        this.scope = this.scopes.MONTH;
      }
    },
  },
}
</script>
