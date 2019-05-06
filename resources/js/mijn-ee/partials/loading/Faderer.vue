<template>

  <div v-if="loading" class="loading-faderer"></div>

</template>

<style lang="scss" scoped>
@import '../../variables';

.loading-faderer {
  @include overlay;
  background-color: $theme-primary;
  opacity: 0.5;
}

</style>

<script>
export default {
  name: 'LoadingFaderer',
  props: ['loading'],
}
</script>
