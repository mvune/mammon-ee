<template>

  <VuePerfectScrollbar class="loading-container p-3">
    <label class="prim-head-sm">Periode</label>
    <DateFilter />

    <label class="prim-head-sm">Rekeningen</label>
    <AccountSelect v-stream:selected="accounts$" :accounts="accounts" />

    <label class="prim-head-sm">Categorieën</label>
    <CategorySelect v-stream:selected="categories$" :categories="categories" />

    <LoadingSpinner :loading="isBusy" />
    <LoadingFaderer class="faderer" :loading="isBusy" />
  </VuePerfectScrollbar>

</template>

<style lang="scss" scoped>
@import '@/mijn-ee/variables.scss';

.loading-container {
  height: calc(100vh - #{$header-height} - 2px);
}

.faderer {
  background-color: $aside-menu-bg;
}
</style>

<script>
import { mapState } from 'vuex'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import AccountSelect from '@/mijn-ee/partials/filters/AccountSelect'
import CategorySelect from '@/mijn-ee/partials/filters/CategorySelect'
import DateFilter from '@/mijn-ee/partials/filters/DateFilter'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import LoadingFaderer from '@/mijn-ee/partials/loading/Faderer'

export default {
  name: 'DefaultAside',
  components: {
    AccountSelect,
    CategorySelect,
    DateFilter,
    LoadingSpinner,
    LoadingFaderer,
    VuePerfectScrollbar,
  },
  domStreams: [
    'accounts$',
    'categories$',
  ],
  computed: {
    ...mapState({
      accounts: state => state.filters.accounts,
      categories: state => state.filters.categories,
      isBusy: state => state.filters.isBusy,
    }),
  },
  created () {
    this.$store.dispatch('fetchFiltersData', this);
    this.$store.dispatch('setSelectedAccounts', this.accounts$);
    this.$store.dispatch('setSelectedCategories', this.categories$);
  },
}
</script>
