<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col lg="11">
        <DailyBar />
      </b-col>
    </b-row>

    <b-row>
      <b-col lg="11">
        <MonthlySheet />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import DailyBar from './daily/Bar'
import MonthlySheet from './monthly/Sheet'

export default {
  name: 'Dashboard',
  components: { DailyBar, MonthlySheet },
}
</script>
