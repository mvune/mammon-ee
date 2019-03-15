<template>
  <AppHeaderDropdown right>
    <template slot="header">
      <i class="fa fa-user-o"></i>
    </template>
    <template slot="dropdown">
      <b-dropdown-item><i class="fa fa-user" /> Profiel</b-dropdown-item>
      <b-dropdown-item to="/logout" @click="logout($event)"><i class="fa fa-lock" /> Log uit</b-dropdown-item>

      <form id="logout-form" action="/logout" method="POST" style="display: none;">
        <input type="hidden" name="_token" v-bind:value="token">
      </form>
    </template>
  </AppHeaderDropdown>
</template>

<script>
import { HeaderDropdown as AppHeaderDropdown } from '@coreui/vue'
export default {
  name: 'HeaderAccountDropdown',
  components: {
    AppHeaderDropdown
  },
  data () {
    return {
      token: $('meta[name="csrf-token"]').attr('content')
    }
  },
  methods: {
    logout ($event) {
      $event.preventDefault();
      document.getElementById('logout-form').submit();
    }
  }
}
</script>
