import Vue from 'vue'
import Vuetify from 'vuetify/lib'

import styles from '../sass/custom/_variables.scss'

Vue.use(Vuetify)

export default new Vuetify({
  lang: {
    current: 'nl',
  },
  theme: {
    themes: {
      light: {
        primary: styles.primary,
      }
    }
  }
})
