/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

import 'core-js/es6/promise'
import 'core-js/es6/string'
import 'core-js/es7/array'

import Vue from 'vue'
import VueRx from 'vue-rx'
import BootstrapVue from 'bootstrap-vue'
import Vuelidate from 'vuelidate'
import Chart from 'chart.js'

import App from './mijn-ee/App'
import router from './mijn-ee/router'
import styles from '../sass/custom/_variables.scss'

Vue.use(BootstrapVue)
Vue.use(Vuelidate)
Vue.use(VueRx)

require('./mijn-ee/globals/mixins')
require('./mijn-ee/globals/filters')

Chart.defaults.global.defaultFontColor = styles.dark;
Chart.defaults.global.defaultFontFamily = styles.fontFamilyBody;
Chart.defaults.global.elements.line.borderColor = styles.primary;
Chart.defaults.global.elements.line.borderWidth = 1;
Chart.defaults.global.tooltips.xPadding = 8;
Chart.defaults.global.tooltips.yPadding = 8;

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App },
});
