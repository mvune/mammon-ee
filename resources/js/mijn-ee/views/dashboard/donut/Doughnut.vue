<template>
  <div>
    <h3 class="text-center">
      {{ sum | ee_valuta }}
      <i v-if="side.code === sides.debet.code" class="fa fa-arrow-up text-success" aria-hidden="true"></i>
      <i v-if="side.code === sides.credit.code" class="fa fa-arrow-down text-danger" aria-hidden="true"></i>
    </h3>
    
    <div v-if="data.length === 0">Geen gegevens.</div>

    <DoughnutChart @legend="legend = $event" :data="data" :labels="labels" class="mb-3" />

    <ul class="legend">
      <li v-for="item of legend" :key="item.label">
        <span class="color" :style="{ backgroundColor: item.color }"></span>
        <span :class="{ 'text-muted': !item.value }">{{ item.label }}</span>
      </li>
    </ul>
  </div>
</template>

<style lang="scss" scoped>
.legend {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-flex;
  align-items: center;
  padding: 0.1rem 0.4rem;
}

.color {
  display: inline-block;
  border: 2px solid white;
  width: 2rem;
  height: 1rem;
  margin-right: 0.25rem;
}
</style>

<script>
import DoughnutChart from './Chart'
import { SIDES } from '@/mijn-ee/globals/constants'

export default {
  name: 'Doughnut',
  components: { DoughnutChart },
  props: {
    side: Object,
    data: Array,
    labels: Array,
  },
  data () {
    return {
      legend: [],
      sides: SIDES,
    }
  },
  computed: {
    sum: function () { return _.sum(this.data) || 0 },
  },
}
</script>
