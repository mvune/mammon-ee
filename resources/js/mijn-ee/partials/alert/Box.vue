<template>

    <b-alert
      :show="countDown"
      dismissible
      fade
      class="alert-dashboard"
      :variant="alert.variant"
      @dismiss-count-down="updateCountDown"
      @dismissed="clearAlert"
    >
      <h5>{{ alert.title }}&nbsp;</h5>
      {{ alert.body }}
    </b-alert>

</template>

<script>
import messages from './messages'

export default {
  name: 'AlertBox',
  data() {
    return {
      alert: { },
      countDown: 0,
      showTime: 5,
    }
  },
  mounted () {
    this.$root.$on('show-alert', this.showAlert);
  },
  methods: {
    updateCountDown (countDown) {
      this.countDown = countDown;
    },
    showAlert (alertKey, customAlert = { }) {
      const defaultAlert = messages[alertKey] || { };
      this.alert = { ...defaultAlert, ...customAlert };
      this.countDown = this.showTime;

      if (!messages[alertKey]) {
        console.error(`Alert "${alertKey}" doesn't exist.`);
      }
    },
    clearAlert () {
      this.alert = { };
      this.countDown = 0;
    },
  }
}
</script>
