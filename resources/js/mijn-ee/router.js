import Vue from 'vue'
import Router from 'vue-router'

// Containers
const DefaultContainer = () => import('@/mijn-ee/container/DefaultContainer')

// Views
const Account = () => import('@/mijn-ee/views/Account')
const Dashboard = () => import('@/mijn-ee/views/Dashboard')
const Page404 = () => import('@/mijn-ee/views/Page404')

Vue.use(Router)

export default new Router({
  mode: 'history',
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/mijn-ee',
      redirect: '/mijn-ee/dashboard',
      name: 'Mijn-ee',
      component: DefaultContainer,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
        },
        {
          path: 'account',
          name: 'Account',
          component: Account,
        },
        {
          path: '*',
          meta: { label: 'Niet gevonden' },
          component: Page404,
        }
      ]
    }
  ]
})
