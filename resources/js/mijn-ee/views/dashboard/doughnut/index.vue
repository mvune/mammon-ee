<template>
  <DoughnutChart :data="data" :labels="labels" />
</template>

<script>
import DoughnutChart from './Chart'

export default {
  name: 'Doughnut',
  components: { DoughnutChart },
  props: {
    data: Array,
    labels: Array,
  },
}
</script>
