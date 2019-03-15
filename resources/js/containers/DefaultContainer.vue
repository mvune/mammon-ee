<template>
  <div class="app">
    <AppHeader fixed>
      <SidebarToggler class="d-lg-none" display="md" mobile />
      <a class="navbar-brand" href="/">
        <img class="navbar-brand-full" src="/images/mammon-ee-large.png" height="16" alt="Mammon-ee">
        <img class="navbar-brand-minimized" src="/favicon.png" width="30" height="30" alt="ee">
      </a>
      <SidebarToggler class="d-md-down-none" display="lg" />
      
      <b-navbar-nav class="ml-auto mr-3">
        <HeaderAccountDropdown/>
      </b-navbar-nav>
    </AppHeader>

    <div class="app-body">
      <AppSidebar fixed>
        <SidebarHeader/>
        <SidebarForm/>
        <SidebarNav :navItems="nav"></SidebarNav>
        <SidebarFooter/>
        <SidebarMinimizer/>
      </AppSidebar>
      <main class="main">
        <Breadcrumb :list="list"/>
        <div class="container-fluid">
          <router-view></router-view>
        </div>
      </main>
    </div>
    
    <TheFooter>
      <!--footer-->
      <div class="ml-auto">
        Powered by <a href="https://coreui.io">CoreUI for Vue</a>
      </div>
    </TheFooter>
  </div>
</template>

<script>
import nav from '@/_nav'
import { Header as AppHeader, SidebarToggler, Sidebar as AppSidebar, SidebarFooter, SidebarForm, SidebarHeader, SidebarMinimizer, SidebarNav, Footer as TheFooter, Breadcrumb } from '@coreui/vue'
import HeaderAccountDropdown from './HeaderAccountDropdown'

export default {
  name: 'DefaultContainer',
  components: {
    AppHeader,
    AppSidebar,
    TheFooter,
    Breadcrumb,
    HeaderAccountDropdown,
    SidebarForm,
    SidebarFooter,
    SidebarToggler,
    SidebarHeader,
    SidebarNav,
    SidebarMinimizer
  },
  data () {
    return {
      nav: nav.items
    }
  },
  computed: {
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched.filter((route) => route.name || route.meta.label )
    }
  }
}
</script>
