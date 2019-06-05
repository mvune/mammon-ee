<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col>
        <Bar />
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <Sheet />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Bar from './bar'
import Sheet from './sheet'

export default {
  name: 'Dashboard',
  components: { Bar, Sheet },
}
</script>
