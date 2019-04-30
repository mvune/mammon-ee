Vue.mixin({
  methods: {
    ee_showAlert (...args) {
      this.$root.$emit('show-alert', ...args);
    },
    ee_errorHandler (e) {
      if (e.response) {
        switch (e.response.status) {
          case 401:
            this.ee_showAlert('unauthenticated');
            window.location.reload(true);
            break;
          case 422:
            this.ee_showAlert('defaultWrongInput', { body: _.find(e.response.data.errors)[0] });
            break;
          default:
            this.ee_showAlert('defaultError');
        }
      }
    }
  }
})
