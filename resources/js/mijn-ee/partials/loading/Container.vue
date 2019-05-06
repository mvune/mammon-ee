<template>

  <div class="loading-container">
    <slot></slot>

    <LoadingSpinner :loading="loading" />
    <LoadingFaderer :loading="loading" />
  </div>

</template>

<style lang="scss" scoped>

.loading-container {
  position: relative;
  width: 100%;
}

</style>

<script>
import LoadingSpinner from './Spinner'
import LoadingFaderer from './Faderer'

export default {
  name: 'LoadingContainer',
  components: { LoadingSpinner, LoadingFaderer },
  props: ['loading'],
}
</script>
