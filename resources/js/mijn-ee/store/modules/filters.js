import { forkJoin } from 'rxjs'
import { finalize, take, pluck, debounceTime } from 'rxjs/operators'
import * as AccountService from '@/mijn-ee/services/AccountService'
import * as CategoryService from '@/mijn-ee/services/CategoryService'
import { SCOPES } from '@/mijn-ee/globals/constants'

const state = {
  accounts: [],
  categories: [],
  selectedAccounts$: null,
  selectedAccounts: null,
  selectedCategories$: null,
  selectedCategories: null,
  month: null,
  year: (new Date).getFullYear(),
  scope: SCOPES.YEAR,
  isBusy: false,
}

const actions = {
  fetchFiltersData ({ commit }, self) {
    commit('setIsBusy', true);

    forkJoin(
      AccountService.getAccounts(),
      CategoryService.getCategories(),
    ).pipe(
      take(1),
      finalize(() => commit('setIsBusy', false)),
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
}

const mutations = {
  setAccounts (state, payload) { state.accounts = payload },
  setCategories (state, payload) { state.categories = payload },
  setSelectedAccounts$ (state, payload) { state.selectedAccounts$ = payload },
  setSelectedAccounts (state, payload) { state.selectedAccounts = payload },
  setSelectedCategories$ (state, payload) { state.selectedCategories$ = payload },
  setSelectedCategories (state, payload) { state.selectedCategories = payload },
  setMonth (state, value) { state.month = value },
  setYear (state, value) { state.year = value },
  setScope (state, value) { state.scope = value },
  setIsBusy (state, value) { state.isBusy = value },
}

export default {
  state,
  actions,
  mutations,
}
