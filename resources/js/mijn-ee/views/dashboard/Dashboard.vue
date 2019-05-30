<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col lg="11" class="mb-2">
        <Filters />
      </b-col>
    </b-row>

    <b-row>
      <b-col lg="11">
        <Bar />
      </b-col>
    </b-row>

    <b-row>
      <b-col lg="11">
        <Sheet />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Filters from './filters'
import Bar from './bar'
import Sheet from './sheet'

export default {
  name: 'Dashboard',
  components: { Filters, Bar, Sheet },
}
</script>
