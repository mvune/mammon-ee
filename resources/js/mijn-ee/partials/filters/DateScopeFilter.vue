<template>
  
  <b-row>
    <b-col sm="6" class="py-0">
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
  
    <b-col sm="6" class="py-0">
      <b-form-radio-group v-model="scope" buttons button-variant="outline-primary" class="float-right">
        <b-form-radio :value="scopes.MONTH">Maand</b-form-radio>
        <b-form-radio :value="scopes.YEAR">Jaar</b-form-radio>
      </b-form-radio-group>
    </b-col>
  </b-row>

</template>

<style lang="scss" scoped>
.time-select {
  font-size: 0.875rem;
  width: 48%;
}
</style>

<script>
import { mapState } from 'vuex'
import { MONTHS, SCOPES } from '@/mijn-ee/globals/constants'
import * as ChartService from '@/mijn-ee/services/ChartService'

export default {
  name: 'DateScopeFilter',
  data () {
    return {
      scopes: SCOPES,
      months: [],
      years: [
        { value: (new Date).getFullYear(), text: (new Date).getFullYear() },
      ],
      firstYear: null,
      lastYear: null,
      firstMonth: null,
      lastMonth: null,
    }
  },
  computed: {
    scope: {
      get: function () { return this.$store.state.filters.scope },
      set: function (value) { this.$store.dispatch('setScope', value) }
    },
    month: {
      get: function () { return this.$store.state.filters.month },
      set: function (value) { this.$store.dispatch('setMonth', value) }
    },
    year: {
      get: function () { return this.$store.state.filters.year },
      set: function (value) { this.$store.dispatch('setYear', value) }
    },
  },
  created () {
    ChartService.getBoundaryDates().subscribe(this.handleResponse, this.ee_errorHandler);
  },
  methods: {
    handleResponse (res) {
      const fromDate = res.data.dateFilter.fromDate;
      const toDate = res.data.dateFilter.toDate;
      this.firstYear = fromDate ? new Date(fromDate).getFullYear() : (new Date).getFullYear();
      this.lastYear = toDate ? new Date(toDate).getFullYear() : (new Date).getFullYear();
      this.firstMonth = fromDate ? new Date(fromDate).getMonth() : null;
      this.lastMonth = toDate ? new Date(toDate).getMonth() : null;
      this.populateYearSelect();
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

        // If `this.month` is now out of scope, set to first available month.
        if (fromMonth && (!this.month || fromMonth > this.month)) {
          this.month = fromMonth + 1;
        }
        // If `this.month` is now out of scope, set to last available month.
        if (toMonth && (!this.month || toMonth < this.month)) {
          this.month = toMonth;
        }
      } else {
        this.months = [];
      }
    },
  },
  watch: {
    year: function () {
      this.populateMonthSelect();
    },
    scope: function () {
      this.populateMonthSelect();
    },
  },
}
</script>
