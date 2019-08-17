import { forkJoin } from 'rxjs'
import { finalize, take, pluck, debounceTime } from 'rxjs/operators'
import * as AccountService from '@/mijn-ee/services/AccountService'
import * as CategoryService from '@/mijn-ee/services/CategoryService'
import { SCOPES } from '@/mijn-ee/globals/constants'

const state = {
  accounts: [],
  categories: [],
  dateFrom$: null,
  dateTo$: null,
  selectedAccounts$: null,
  selectedAccounts: null,
  selectedCategories$: null,
  selectedCategories: null,
  month: null,
  year: (new Date).getFullYear(),
  scope: SCOPES.YEAR,
  areReady: true,
  filters$: null,
}

const actions = {
  fetchFiltersData ({ commit }, self) {
    commit('setAreReady', false);

    forkJoin(
      AccountService.getAccounts(),
      CategoryService.getCategories(),
    ).pipe(
      take(1),
      finalize(() => commit('setAreReady', true)),
    ).subscribe(response => {
      commit('setAccounts', response[0]);
      commit('setCategories', response[1]);
    }, self.ee_errorHandler);
  },
  setSelectedAccounts (ctx, value) {
    value = value.pipe(debounceTime(750), pluck('event', 'msg'));
    ctx.commit('setSelectedAccounts$', value);
    value.subscribe(accounts => ctx.commit('setSelectedAccounts', accounts));
  },
  setSelectedCategories (ctx, value) {
    value = value.pipe(debounceTime(750), pluck('event', 'msg'));
    ctx.commit('setSelectedCategories$', value);
    value.subscribe(categories => ctx.commit('setSelectedCategories', categories));
  },
  setMonth (ctx, value) {
    ctx.commit('setMonth', value);
    ctx.dispatch('setDonutData');
  },
  setYear (ctx, value) {
    ctx.commit('setYear', value);
    ctx.dispatch('setDonutData');
  },
  setScope (ctx, value) {
    ctx.commit('setScope', value);
    ctx.dispatch('setDonutData');
  },
  setDateFrom (ctx, value) {
    ctx.commit('setDateFrom$', value);
    ctx.dispatch('setDonutData');
  },
  setDateTo (ctx, value) {
    ctx.commit('setDateTo$', value);
    ctx.dispatch('setDonutData');
  },
  setFilters (ctx, value) {
    ctx.commit('setFilters$', value);
  },
}

const mutations = {
  setAccounts (state, payload) { state.accounts = payload },
  setCategories (state, payload) { state.categories = payload },
  setDateFrom$ (state, payload) { state.dateFrom$ = payload },
  setDateTo$ (state, payload) { state.dateTo$ = payload },
  setSelectedAccounts$ (state, payload) { state.selectedAccounts$ = payload },
  setSelectedAccounts (state, payload) { state.selectedAccounts = payload },
  setSelectedCategories$ (state, payload) { state.selectedCategories$ = payload },
  setSelectedCategories (state, payload) { state.selectedCategories = payload },
  setMonth (state, value) { state.month = value },
  setYear (state, value) { state.year = value },
  setScope (state, value) { state.scope = value },
  setAreReady (state, value) { state.areReady = value },
  setFilters$ (state, value) { state.filters$ = value },
}

export default {
  state,
  actions,
  mutations,
}
