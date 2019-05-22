<template>
  <div class="animated fadeIn">
    <div class="d-flex flex-column justify-content-center align-items-center">
      <h1>404 | Pagina niet gevonden</h1>
      <p>Heel jammer dit. Deze pagina bestaat niet.</p>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@import '../variables';

.d-flex {
  height: 62vh;
}

h1 {
  color: $theme-quaternary;
}
</style>

<script>
export default {
  name: 'page404'
}
</script>
