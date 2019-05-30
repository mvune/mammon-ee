import Vue from 'vue'
import Vuex from 'vuex'
import { SCOPES } from '@/mijn-ee/globals/constants'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    scope: SCOPES.YEAR,
    month: null,
    year: (new Date).getFullYear(),
  },
  actions: {
    setScope (ctx, value) { ctx.commit('setScope', value) },
    setMonth (ctx, value) { ctx.commit('setMonth', value) },
    setYear (ctx, value) { ctx.commit('setYear', value) },
  },
  mutations: {
    setScope (state, scope) { state.scope = scope },
    setMonth (state, month) { state.month = month },
    setYear (state, year) { state.year = year },
  },
})
