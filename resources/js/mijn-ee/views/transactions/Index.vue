<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col md="10" lg="8">
        <AccountSelect v-on:update:selected="getItems(currentPage, $event)" />

        <div class="ee-spinner-container">
          <b-table
            id="transactions-table"
            :items="items"
            :fields="fields"
            :busy.sync="isBusy"
            :current-page="currentPage"
            selectable
            select-mode="single"
            @row-clicked="rowClicked"
            show-empty
          >
            <template slot="date" slot-scope="row">
              {{ row.value | ee_date }}
            </template>

            <template slot="amount" slot-scope="row">
              <template v-if="row.value > 0">
                <span class="text-success amount-positive">{{ row.value | ee_valuta }}</span>
              </template>

              <template v-else-if="row.value < 0">
                <span class="text-primary">{{ row.value | ee_valuta }}</span>
              </template>

              <template v-else>
                <span>{{ row.value | ee_valuta }}</span>
              </template>
            </template>

            <template slot="row-details" slot-scope="row">
              <Details :transaction="row.item" />
            </template>

            <template v-if="items.length == 0" v-slot:table-busy>
              <div class="d-flex justify-content-center">
                <b-spinner small variant="primary" label="Laden..."></b-spinner>
              </div>
            </template>

            <template v-slot:empty>
              Geen transacties.
            </template>

          </b-table>

          <LoadingSpinner v-if="items.length > 0" :loading="isBusy" />
        </div>

        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="center"
          aria-controls="transactions-table"
        ></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>

<style lang="scss" scoped>
.amount-positive {
  font-size: 1.1em;
  font-weight: bold;
}
</style>

<script>
import Details from './partials/Details'
import AccountSelect from './partials/AccountSelect'
import LoadingSpinner from '@/mijn-ee/partials/LoadingSpinner'

export default {
  name: 'TransactionsIndex',
  components: { Details, AccountSelect, LoadingSpinner },
  data () {
    return {
      isBusy: false,
      fields: [
        { key: 'date', label: 'Datum' },
        { key: 'counterparty_name', label: 'Tegenpartij' },
        { key: 'amount', label: 'Bedrag', tdClass: 'amount-column' },
      ],
      items: [], // Transactions
      totalRows: 0,
      perPage: 0,
      currentPage: 1,
    }
  },
  mounted () {
    this.getItems();
  },
  methods: {
    getItems (page, accounts) {
      if (_.isArray(accounts) && accounts.length == 0) {
        this.clearItems();
        return;
      }
      this.isBusy = true;
      page = page || this.currentPage;
      const params = new URLSearchParams;
      params.append('page', page);
      if (accounts) params.append('accounts', accounts);

      return axios.get('transactions?' + params.toString())
        .then(response => {
          this.items = response.data.data;
          this.formatItems();
          this.totalRows = response.data.meta.total;
          this.perPage = response.data.meta.per_page;
          this.currentPage = response.data.meta.current_page;
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    formatItems () {
      for (let item of this.items) {
        item._showDetails = false;
      }
    },
    clearItems () {
      this.items = [];
      this.totalRows = 0;
      this.perPage = 0;
      this.currentPage = 1;
    },
    rowClicked (row) {
      this.toggleDetails(row);
    },
    toggleDetails (item) {
      const current = item._showDetails;
      this.hideDetailsAll();
      item._showDetails = !current;
    },
    hideDetailsAll () {
      for (let item of this.items) {
        item._showDetails = false;
      }
    },
  },
  watch: {
    currentPage: function (newVal, oldVal) {
      this.getItems(newVal).then(() => window.scrollTo(0, 0));
    }
  }
}
</script>
