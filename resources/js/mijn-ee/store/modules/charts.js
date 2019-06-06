import { SCOPES, SIDES } from '@/mijn-ee/globals/constants'

const state = {
  sheetData: [],
  donutDebetLabels: [],
  donutCreditLabels: [],
  donutDebetData: [],
  donutCreditData: [],
}

const actions = {
  setSheetData (ctx, value) {
    ctx.commit('setSheetData', value);
    ctx.dispatch('setDonutData');
  },
  setDonutData (ctx) {
    if (ctx.state.sheetData) {
      const month = ctx.rootState.filters.month;
      const year = ctx.rootState.filters.year;
      const scope = ctx.rootState.filters.scope;
      let data = ctx.state.sheetData[year];

      data = data ? data.filter(item => !item.is_totals_row && !item.is_subtotals_row) : [];

      let debetData = data.filter(item => item.side === SIDES.debet.code);
      let creditData = data.filter(item => item.side === SIDES.credit.code);

      let debetLabels = debetData.map(item => item.category);
      let creditLabels = creditData.map(item => item.category);

      if (scope === SCOPES.YEAR) {
        debetData = debetData.map(item => item.year_total || undefined);
        creditData = creditData.map(item => item.year_total || undefined);
      }
      else if (scope === SCOPES.MONTH) {        
        debetData = debetData.map(item => item[month] || undefined);
        creditData = creditData.map(item => item[month] || undefined);
      }

      ctx.commit('setDonutDebetData', debetData);
      ctx.commit('setDonutCreditData', creditData);
      ctx.commit('setDonutDebetLabels', debetLabels);
      ctx.commit('setDonutCreditLabels', creditLabels);
    }
  },
  setDonutDebetData (ctx, value) { ctx.commit('setDonutDebetData', value) },
  setDonutCreditData (ctx, value) { ctx.commit('setDonutCreditData', value) },
}

const mutations = {
  setSheetData (state, value) { state.sheetData = value },
  setDonutDebetData (state, value) { state.donutDebetData = value },
  setDonutCreditData (state, value) { state.donutCreditData = value },
  setDonutDebetLabels (state, value) { state.donutDebetLabels = value },
  setDonutCreditLabels (state, value) { state.donutCreditLabels = value },
}

export default {
  state,
  actions,
  mutations,
}
