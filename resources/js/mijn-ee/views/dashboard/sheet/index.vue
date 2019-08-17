<template>

<div>
  <div class="nav-container mb-2">
    <b-form-select class="year-select"
      v-model="year"
      :options="years"
    ></b-form-select>
  </div>

  <VuePerfectScrollbar class="sheet-container ee-spinner-container">
    <b-table
      id="sheet-table"
      :items="scopedData"
      :fields="fields"
      :busy.sync="isBusy"
      :tbody-tr-class="trClass"
    >
      <template v-for="col in [1,2,3,4,5,6,7,8,9,10,11,12,'year_total']" :slot="col" slot-scope="data">
        <template v-if="typeof data.value === 'number'">
          <template v-if="data.item.is_totals_row">
            <template v-if="data.item.is_net_row && data.value < 0">
              <span :key="col" class="text-danger">
                {{ data.value | ee_valuta(false) }}
              </span>
            </template>
            <template v-else>
              {{ data.value | ee_valuta(false, false) }}
            </template>
          </template>
          <template v-else>
            {{ data.value | ee_valuta(false, false, false) }}
          </template>
        </template>
        <template v-else>
          {{ data.value }}
        </template>
      </template>
    </b-table>

    <LoadingSpinner :loading="isBusy" />
  </VuePerfectScrollbar>

  <div class="nav-container mt-2">
    <b-form-select class="year-select"
      v-model="year"
      :options="years"
    ></b-form-select>
  </div>
</div>

</template>

<style lang="scss" scoped>
@import '@/mijn-ee/variables.scss';

.sheet-container {
  border: 2px solid $extra-dark;
}

.nav-container {
  display: flex;
  justify-content: center;
}

.year-select {
  width: 100px;
}

.table {
  border: 0;
  margin-bottom: 0;
}
</style>

<script>
import { mapState } from 'vuex'
import { combineLatest } from 'rxjs'
import { pluck, switchMap, tap, startWith, filter } from 'rxjs/operators'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import { MONTHS } from '@/mijn-ee/globals/constants'
import * as ChartService from '@/mijn-ee/services/ChartService'

export default {
  name: 'Sheet',
  components: { LoadingSpinner, VuePerfectScrollbar },
  data () {
    return {
      isBusy: false,
      year: (new Date).getFullYear(),
      years: [
        { value: (new Date).getFullYear(), text: (new Date).getFullYear() },
      ],
      fields: [
        { key: 'category', label: 'Inkomen', tdClass: 'sheet-td-left' },
        { key: '1', label: MONTHS[0].text, tdClass: '' },
        { key: '2', label: MONTHS[1].text, tdClass: '' },
        { key: '3', label: MONTHS[2].text, tdClass: '' },
        { key: '4', label: MONTHS[3].text, tdClass: '' },
        { key: '5', label: MONTHS[4].text, tdClass: '' },
        { key: '6', label: MONTHS[5].text, tdClass: '' },
        { key: '7', label: MONTHS[6].text, tdClass: '' },
        { key: '8', label: MONTHS[7].text, tdClass: '' },
        { key: '9', label: MONTHS[8].text, tdClass: '' },
        { key: '10', label: MONTHS[9].text, tdClass: '' },
        { key: '11', label: MONTHS[10].text, tdClass: '' },
        { key: '12', label: MONTHS[11].text, tdClass: '' },
        { key: 'year_total', label: 'Jaar totaal', tdClass: 'sheet-td-right' },
      ],
      data: {},
      dataSubscription: null,
      emptyData: Array(15).fill({ 1: "\0" }),
    }
  },
  computed: {
    scopedData: function () {
      return this.data[this.year] || this.emptyData;
    },
    ...mapState({
      filters$: state => state.filters.filters$,
    }),
  },
  created () {
    this.getData();
  },
  methods: {
    getData (skipFirst) {
      if (this.filters$) {
        this.dataSubscription = this.filters$(skipFirst).pipe(
          tap(() => this.isBusy = true),
          switchMap((filters) => {
            return ChartService.getSheetData(filters);
          }),
          tap(() => this.isBusy = false),
        ).subscribe(
          this.handleResponse,
          this.ee_errorHandler
        );
      }
    },
    handleResponse (res) {
      this.data = res.data || {};
      this.$store.dispatch('setSheetData', this.data);
    },
    populateYearSelect () {
      const years = Object.keys(this.data);
      this.years = years.map(y => ({ value: y, text: y }));

      if (this.years.length > 0 && !_.includes(years, this.year)) {
        this.year = this.years[this.years.length - 1].value;
      }
    },
    trClass (item, type) {
      let trClass = '';
      if (item && item.is_header_row) trClass += ' header-row';
      if (item && item.is_totals_row) trClass += ' totals-row';
      if (item && item.is_net_row) trClass += ' net-row';
      return trClass;
    },
  },
  watch: {
    filters$ () {
      this.getData(true);
    },
    data () {
      this.populateYearSelect();
    },
  },
  beforeDestroy () {
    if (this.dataSubscription) {
      this.dataSubscription.unsubscribe();
    }
  },
}
</script>
