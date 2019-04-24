<template>

  <div v-if="loading" class="ee-fade"></div>

</template>

<script>
export default {
  name: 'LoadingFaderer',
  props: ['loading'],
}
</script>
