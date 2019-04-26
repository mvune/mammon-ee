Vue.mixin({
  methods: {
    ee_showAlert (...args) {
      this.$root.$emit('show-alert', ...args);
    },
    ee_errorHandler (e) {
      if (e.response && e.response.status == 422) {
        this.ee_showAlert('defaultWrongInput', { body: _.find(e.response.data.errors)[0] });
      } else {
        this.ee_showAlert('defaultError');
      }
    }
  }
})
