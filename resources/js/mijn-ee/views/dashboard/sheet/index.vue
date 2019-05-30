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
          {{ data.value | ee_valuta(false, false, false) }}
        </template>
        <template v-else>
          {{ data.value }}
        </template>
      </template>
    </b-table>

    <LoadingSpinner v-if="data.length > 0" :loading="isBusy" />
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
        { key: 'year_total', label: 'Jaar totaal' },
      ],
      data: [],
      emptyData: Array(20).fill({ 1: "\0" }),
    }
  },
  computed: {
    year: {
      get: function () { return this.$store.state.year },
      set: function (value) { this.$store.dispatch('setYear', value) }
    },
    scopedData: function () {
      return this.data[this.year] || this.emptyData;
    },
  },
  created () {
    this.getData();
  },
  methods: {
    getData () {
      this.isBusy = true;

      ChartService.getSheetData().subscribe(
        this.handleResponse,
        this.ee_errorHandler,
        () => this.isBusy = false
      );
    },
    handleResponse (res) {
      this.data = res.data;
    },
    trClass (item, type) {
      if (item && item.is_header_row) return 'header-row';
    },
  },
}
</script>
