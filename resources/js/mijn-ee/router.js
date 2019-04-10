import Vue from 'vue'
import Router from 'vue-router'

// Containers
const DefaultContainer = () => import('@/mijn-ee/containers/DefaultContainer')

// Views
const Accounts = () => import('@/mijn-ee/views/Accounts')
const Dashboard = () => import('@/mijn-ee/views/Dashboard')
const Page404 = () => import('@/mijn-ee/views/Page404')
const Profile = () => import('@/mijn-ee/views/Profile')
const Transactions = () => import('@/mijn-ee/views/Transactions')

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
          path: 'transacties',
          name: 'Transacties',
          component: Transactions,
        },
        {
          path: 'rekeningen',
          name: 'Rekeningen',
          component: Accounts,
        },
        {
          path: 'account',
          name: 'Account',
          component: Profile,
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
