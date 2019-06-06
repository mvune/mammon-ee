import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import charts from './modules/charts'
import filters from './modules/filters'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    charts,
    filters,
  },
})
