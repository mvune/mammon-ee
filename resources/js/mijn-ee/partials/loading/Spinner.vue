<template>

  <b-spinner
    v-if="loading"
    small
    variant="primary"
    label="Laden..."
    class="loading-spinner"
  ></b-spinner>

</template>

<style lang="scss" scoped>

.loading-spinner {
  position: absolute;
  top: calc(50% - 0.5rem);
  left: calc(50% - 0.5rem);
  z-index: 101;
}

</style>

<script>
export default {
  name: 'LoadingSpinner',
  props: ['loading'],
}
</script>
