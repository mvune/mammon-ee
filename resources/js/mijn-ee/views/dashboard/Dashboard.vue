<template>
  <div class="animated fadeIn">
    <b-tabs class="bare-tabs">
      <b-tab>
        <template slot="title">
          <i class="icon-pie-chart icons" aria-label="Donut"></i>
        </template>

        <b-row>
          <b-col>
            <Donut />
          </b-col>
        </b-row>
      </b-tab>

      <b-tab>
        <template slot="title">
          <i class="icon-list icons" aria-label="Sheet"></i>
        </template>

        <b-row>
          <b-col>
            <Sheet class="mb-3" />
          </b-col>
        </b-row>
      </b-tab>

      <b-tab>
        <template slot="title">
          <i class="icon-chart icons" aria-label="Saldo"></i>
        </template>

        <b-row>
          <b-col>
            <Bar />
          </b-col>
        </b-row>
      </b-tab>
    </b-tabs>
  </div>
</template>

<style lang="scss" scoped>
i.icons {
  font-size: 1.2rem;
}
</style>

<script>
import Bar from './bar'
import Sheet from './sheet'
import Donut from './donut'

export default {
  name: 'Dashboard',
  components: { Bar, Sheet, Donut },
}
</script>
