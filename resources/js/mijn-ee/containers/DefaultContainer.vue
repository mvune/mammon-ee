<template>
  <div class="app animated fadeIn">
    <AppHeader fixed>
      <SidebarToggler class="d-lg-none" display="md" mobile />
      <a class="navbar-brand" href="/">
        <img class="navbar-brand-full" src="/images/mammon-ee-large.png" height="16" alt="Mammon-ee">
        <img class="navbar-brand-minimized" src="/favicon.png" width="30" height="30" alt="ee">
      </a>
      <SidebarToggler class="d-md-down-none" display="lg" />

      <Breadcrumb :list="list"/>
      
      <b-navbar-nav class="ml-auto mr-3">
        <AccountDropdown/>
      </b-navbar-nav>

      <div class="mr-2">
        <AsideToggler class="d-none d-lg-block" />
        <AsideToggler class="d-lg-none" mobile />
      </div>
    </AppHeader>

    <AlertBox />

    <div class="app-body">
      <AppSidebar fixed>
        <SidebarHeader/>
        <SidebarForm/>
        <SidebarNav :navItems="nav"></SidebarNav>
        <SidebarFooter/>
        <SidebarMinimizer/>
      </AppSidebar>

      <main class="main">
        <div class="main-inner container-fluid">
          <router-view></router-view>
        </div>
      </main>

      <AppAside fixed>
        <DefaultAside />
      </AppAside>
    </div>
    
    <TheFooter>
      <!--footer-->
      <div class="ml-auto">
        Powered by <a href="https://coreui.io" target="_blank" rel="noopener">CoreUI for Vue</a>
      </div>
    </TheFooter>
  </div>
</template>

<style>
  p {
    font-size: 14px;
  }
</style>

<script>
import {
  Header as AppHeader,
  SidebarToggler,
  Sidebar as AppSidebar,
  SidebarFooter,
  SidebarForm,
  SidebarHeader,
  SidebarMinimizer,
  SidebarNav,
  Aside as AppAside,
  AsideToggler,
  Footer as TheFooter,
  Breadcrumb } from '@coreui/vue'
import nav from './nav'
import DefaultAside from './partials/DefaultAside'
import AccountDropdown from './partials/AccountDropdown'
import AlertBox from '@/mijn-ee/partials/alert/Box'

export default {
  name: 'DefaultContainer',
  components: {
    AppHeader,
    AppSidebar,
    AppAside,
    AsideToggler,
    TheFooter,
    Breadcrumb,
    DefaultAside,
    AccountDropdown,
    AlertBox,
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
