<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col md="11" lg="3">
        <LoadingContainer :loading="isBusyFilters">
          <label class="prim-head-sm">Rekeningen</label>
          <AccountSelect v-stream:selected="accounts$" :accounts="accounts" />

          <label class="prim-head-sm">Categorieën</label>
          <CategorySelect v-stream:selected="categories$" :categories="categories" />
        </LoadingContainer>
      </b-col>

      <b-col md="11" lg="8">
        <div class="ee-spinner-container">
          <b-table
            id="transactions-table"
            :items="transactions"
            :fields="fields"
            :busy.sync="isBusyTransactions"
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
                <span class="text-success amount-positive">{{ row.value | ee_valuta(true) }}</span>
              </template>

              <template v-else-if="row.value < 0">
                <span class="text-primary">{{ row.value | ee_valuta(true) }}</span>
              </template>

              <template v-else>
                <span>{{ row.value | ee_valuta(true) }}</span>
              </template>
            </template>

            <template slot="row-details" slot-scope="row">
              <Details :transaction="row.item" />
            </template>

            <template v-if="transactions.length == 0" v-slot:table-busy>
              <div class="d-flex justify-content-center">
                <b-spinner small variant="primary" label="Laden..."></b-spinner>
              </div>
            </template>

            <template v-slot:empty>
              Geen transacties.
            </template>

          </b-table>

          <LoadingSpinner v-if="transactions.length > 0" :loading="isBusyTransactions" />
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
import { combineLatest, forkJoin } from 'rxjs'
import { pluck, switchMap, tap, startWith, skip, finalize } from 'rxjs/operators'
import * as AccountService from '@/mijn-ee/services/AccountService'
import * as CategoryService from '@/mijn-ee/services/CategoryService'
import * as TransactionService from '@/mijn-ee/services/TransactionService'
import LoadingContainer from '@/mijn-ee/partials/loading/Container'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import Details from './partials/Details'
import AccountSelect from './partials/AccountSelect'
import CategorySelect from './partials/CategorySelect'

export default {
  name: 'Transactions',
  components: { Details, AccountSelect, CategorySelect, LoadingContainer, LoadingSpinner },
  data () {
    return {
      isBusyTransactions: false,
      isBusyFilters: true,
      fields: [
        { key: 'date', label: 'Datum' },
        { key: 'counterparty_name', label: 'Tegenpartij' },
        { key: 'amount', label: 'Bedrag', tdClass: 'amount-column' },
      ],
      transactions: [],
      totalRows: 0,
      perPage: 0,
      currentPage: 1,
      currentPageCached: 1,
      accounts: [],
      categories: [],
    }
  },
  domStreams: [
    'accounts$',
    'categories$',
    'currentPage$',
  ],
  subscriptions () {
    // Observables
    const transactions$ = combineLatest(
      this.accounts$.pipe(skip(1), pluck('event', 'msg'), startWith(undefined)),
      this.categories$.pipe(skip(1), pluck('event', 'msg'), startWith(undefined)),
      this.currentPage$.pipe(pluck('event', 'msg'), startWith(undefined)),
    ).pipe(
      tap(() => this.isBusyTransactions = true),
      switchMap(([accounts, categories, page]) => {
        const nextPage = this.pageIsChanging(page) ? page : undefined;
        return TransactionService.getTransactions(nextPage, accounts, categories);
      }),
      tap(() => this.isBusyTransactions = false),
    );

    const filters$ = forkJoin(
      AccountService.getAccounts(),
      CategoryService.getCategories(),
    ).pipe(
      finalize(() => this.isBusyFilters = false),
    );

    // Subscriptions
    transactions$.subscribe(this.handleTransactionsResponse, this.ee_errorHandler);
    filters$.subscribe(this.handleFiltersResponse, this.ee_errorHandler);
  },
  methods: {
    handleTransactionsResponse (res) {
      if (res.data) {
        for (let item of res.data.data) {
          item._showDetails = false;
        }
        this.transactions = res.data.data;
        this.totalRows = res.data.meta.total;
        this.perPage = res.data.meta.per_page;
        this.currentPage = res.data.meta.current_page;
      } else {
        this.transactions = [];
        this.totalRows = 0;
        this.perPage = 0;
        this.currentPage = 1;
      }
      window.scrollTo(0, 0);
    },
    handleFiltersResponse (res) {
      [this.accounts, this.categories] = res;
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
      for (let item of this.transactions) {
        item._showDetails = false;
      }
    },
  },
}
</script>
