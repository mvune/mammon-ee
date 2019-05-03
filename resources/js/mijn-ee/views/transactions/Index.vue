<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col md="10" lg="8">
        <AccountSelect v-stream:update:selected="accounts$" />

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
          v-stream:change="currentPage$"
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
import * as TransactionService from '@/mijn-ee/services/TransactionService'
import { combineLatest } from 'rxjs'
import { map, pluck, switchMap, tap, startWith, skip } from 'rxjs/operators'

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
      currentPageCached: 1,
    }
  },
  domStreams: ['accounts$', 'currentPage$'],
  subscriptions () {
    const items$ = combineLatest(
      this.accounts$.pipe(skip(1), pluck('event', 'msg'), startWith(undefined)),
      this.currentPage$.pipe(pluck('event', 'msg'), startWith(undefined)),
    ).pipe(
      tap(() => this.isBusy = true),
      switchMap(([accounts, page]) => {
        const nextPage = this.pageIsChanging(page) ? page : undefined;
        return TransactionService.getTransactions(nextPage, accounts);
      }),
      map(this.formatItems),
      tap(() => this.isBusy = false),
    );

    items$.subscribe(
      this.handleResponse,
      this.ee_errorHandler,
    );
  },
  methods: {
    formatItems (res) {
      if (res.data) {
        for (let item of res.data.data) {
          item._showDetails = false;
        }
      }
      return res;
    },
    handleResponse (res) {
      if (res.data) {
        this.items = res.data.data;
        this.totalRows = res.data.meta.total;
        this.perPage = res.data.meta.per_page;
        this.currentPage = res.data.meta.current_page;
      } else {
        this.items = [];
        this.totalRows = 0;
        this.perPage = 0;
        this.currentPage = 1;
      }
      window.scrollTo(0, 0);
    },
    pageIsChanging (page) {
      if (typeof page === 'undefined') return undefined;
      if (page !== this.currentPageCached) {
        this.currentPageCached = page;
        return true;
      }
      return false;
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
}
</script>
