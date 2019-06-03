<template>
  <div>
    <CategorySideSelect
      v-stream:selected="categoriesDebet$"
      :categories="categories"
      :side="sides.debet" />
    <CategorySideSelect
      v-stream:selected="categoriesCredit$"
      :categories="categories"
      :side="sides.credit" />
  </div>
</template>

<script>
import { combineLatest } from 'rxjs'
import { pluck } from 'rxjs/operators'
import { SIDES } from '@/mijn-ee/globals/constants.js'
import CategorySideSelect from './CategorySideSelect'

export default {
  name: 'CategorySelect',
  components: { CategorySideSelect },
  props: {
    categories: Array,
  },
  data () {
    return {
      sides: SIDES,
    }
  },
  domStreams: [
    'categoriesDebet$',
    'categoriesCredit$',
  ],
  subscriptions () {
    const categories$ = combineLatest(
      this.categoriesDebet$.pipe(pluck('event', 'msg')),
      this.categoriesCredit$.pipe(pluck('event', 'msg')),
    );

    categories$.subscribe(this.handleSelect, this.ee_errorHandler);
  },
  methods: {
    handleSelect ([categoriesDebet, categoriesCredit]) {
      this.$emit('selected', categoriesDebet.concat(categoriesCredit));
    },
  },
}
</script>
