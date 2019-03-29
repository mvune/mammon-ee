<template>
  <AppHeaderDropdown right>
    <template slot="header">
      <i class="fa fa-user-o"></i>
    </template>
    <template slot="dropdown">
      <b-dropdown-item :to="{ name: 'Account' }"><i class="fa fa-user" /> Account</b-dropdown-item>
      <b-dropdown-item to="/mijn-ee/logout" @click.prevent="logout()"><i class="fa fa-lock" /> Log uit</b-dropdown-item>

      <form id="logout-form" action="/logout" method="POST" style="display: none;">
        <input type="hidden" name="_token" v-bind:value="token">
      </form>
    </template>
  </AppHeaderDropdown>
</template>

<script>
import { HeaderDropdown as AppHeaderDropdown } from '@coreui/vue'
export default {
  name: 'AccountDropdown',
  components: {
    AppHeaderDropdown,
  },
  data () {
    return {
      token: $('meta[name="csrf-token"]').attr('content'),
    }
  },
  methods: {
    logout () {
      document.getElementById('logout-form').submit();
    }
  }
}
</script>
