<template>

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

</template>

<style lang="scss" scoped>
@import '@/mijn-ee/variables.scss';

.sheet-container {
  max-height: 80vh;
  border: 2px solid $extra-dark;
}

.table {
  border: 0;
  margin-bottom: 0;
}
</style>

<script>
import { mapState } from 'vuex'
import { combineLatest } from 'rxjs'
import { switchMap, tap, startWith, filter } from 'rxjs/operators'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import { MONTHS } from '@/mijn-ee/globals/constants'
import * as ChartService from '@/mijn-ee/services/ChartService'

export default {
  name: 'MonthlySheet',
  components: { LoadingSpinner, VuePerfectScrollbar },
  data () {
    return {
      isBusy: false,
      fields: [
        { key: 'category', label: 'Inkomen', tdClass: 'sheet-td-left' },
        { key: '1', label: MONTHS[0].text },
        { key: '2', label: MONTHS[1].text },
        { key: '3', label: MONTHS[2].text },
        { key: '4', label: MONTHS[3].text },
        { key: '5', label: MONTHS[4].text },
        { key: '6', label: MONTHS[5].text },
        { key: '7', label: MONTHS[6].text },
        { key: '8', label: MONTHS[7].text },
        { key: '9', label: MONTHS[8].text },
        { key: '10', label: MONTHS[9].text },
        { key: '11', label: MONTHS[10].text },
        { key: '12', label: MONTHS[11].text },
        { key: 'year_total', label: 'Jaar totaal', tdClass: 'sheet-td-right' },
      ],
      data: [],
      dataSubscription: null,
      emptyData: Array(15).fill({ 1: "\0" }),
    }
  },
  computed: {
    year: {
      get: function () { return this.$store.state.filters.year },
      set: function (value) { this.$store.dispatch('setYear', value) }
    },
    scopedData: function () {
      return this.data[this.year] || this.emptyData;
    },
    ...mapState({
      accounts$: state => state.filters.selectedAccounts$,
      accounts: state => state.filters.selectedAccounts,
      categories$: state => state.filters.selectedCategories$,
      categories: state => state.filters.selectedCategories,
    }),
    filtersAreReady () {
      return !!(this.accounts$ && this.categories$);
    },
  },
  created () {
    this.getData();
  },
  beforeDestroy () {
    if (this.dataSubscription) {
      this.dataSubscription.unsubscribe();
    }
  },
  methods: {
    getData (skipFirst) {
      if (this.filtersAreReady) {
        this.dataSubscription = combineLatest(
          this.accounts$.pipe(filter((val, i) => !(skipFirst && i < 1)), startWith(this.accounts)),
          this.categories$.pipe(filter((val, i) => !(skipFirst && i < 1)), startWith(this.categories)),
        ).pipe(
          tap(() => this.isBusy = true),
          switchMap(([accounts, categories]) => {
            return ChartService.getSheetData(accounts, categories);
          }),
          tap(() => this.isBusy = false),
        ).subscribe(
          this.handleResponse,
          this.ee_errorHandler
        );
      }
    },
    handleResponse (res) {
      this.data = res.data || [];
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
    filtersAreReady () {
      this.getData(true);
    },
  },
}
</script>
