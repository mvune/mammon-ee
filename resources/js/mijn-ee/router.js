import Vue from 'vue'
import Router from 'vue-router'

// Containers
const DefaultContainer = () => import('@/mijn-ee/containers/DefaultContainer')

// Views
const Accounts = () => import('@/mijn-ee/views/accounts/Accounts')
const Categories = () => import('@/mijn-ee/views/categories/Categories')
const Dashboard = () => import('@/mijn-ee/views/dashboard/Dashboard')
const Home = () => import('@/mijn-ee/views/home/Home')
const Page404 = () => import('@/mijn-ee/views/Page404')
const Profile = () => import('@/mijn-ee/views/Profile')
const TransactionsAdd = () => import('@/mijn-ee/views/transactions/TransactionsAdd')
const Transactions = () => import('@/mijn-ee/views/transactions/Transactions')

Vue.use(Router)

export default new Router({
  mode: 'history',
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/mijn-ee',
      redirect: '/mijn-ee/home',
      name: 'Mijn-ee',
      component: DefaultContainer,
      children: [
        {
          path: 'home',
          name: 'Home',
          component: Home,
        },
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
        },
        {
          path: 'transacties',
          redirect: '/mijn-ee/transacties/overzicht',
          name: 'Transacties',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'overzicht',
              name: 'Overzicht',
              component: Transactions,
            },
            {
              path: 'toevoegen',
              name: 'Toevoegen',
              component: TransactionsAdd,
            },
          ]
        },
        {
          path: 'categorieen',
          name: 'Categorieën',
          component: Categories,
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
