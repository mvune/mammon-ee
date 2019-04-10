<template>

  <b-spinner
    v-if="loading"
    small
    variant="primary"
    label="Laden..."
    class="ee-spinner"
  ></b-spinner>

</template>

<script>
export default {
  name: 'LoadingSpinner',
  props: ['loading'],
}
</script>
