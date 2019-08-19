import { SIDES } from '@/mijn-ee/globals/constants'

const state = {
  sheetData: [],
  donutDebetData: [],
  donutCreditData: [],
  donutDebetLabels: [],
  donutCreditLabels: [],
}

const actions = {
  setSheetData (ctx, value) {
    ctx.commit('setSheetData', value);
  },
  setDonutData (ctx, value) {
    ctx.commit('setDonutDebetData', value ? value[SIDES.debet.code].map(category => _.toNumber(category.total)) : []);
    ctx.commit('setDonutCreditData', value ? value[SIDES.credit.code].map(category => _.toNumber(category.total)) : []);
    ctx.commit('setDonutDebetLabels', value ? value[SIDES.debet.code].map(category => category.name) : []);
    ctx.commit('setDonutCreditLabels', value ? value[SIDES.credit.code].map(category => category.name) : []);
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
