<template>
  <div class="animated fadeIn">
    <div class="items-container">
      <template v-for="item in navItems">
        <div v-if="item.name !== 'Home'" :key="item.name" class="item">
          <router-link :to="item.url">
            <b-card>
              <div class="mb-2">
                <i :class="item.icon" class="icons gold"></i>
              </div>

              <h3 class="gold">{{ item.name }}</h3>

              <b-card-text class="mt-4">
                {{ item.description }}
              </b-card-text>
            </b-card>
          </router-link>
        </div>
      </template>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@import '@/mijn-ee/variables.scss';

.items-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.item {
  position: relative;
  width: 15rem;
  margin-right: 0.5rem;
  margin-bottom: 0.5rem;
}

.item:after {
  content: "";
  display: block;
  padding-bottom: 100%;
}

.card {
  position: absolute;
  width: 100%;
  height: 100%;
  text-align: center;
  color: $gray-lighter;
  transition: all 0.3s;
}

.card:hover, .item > a:focus .card {
  color: $theme-quaternary;
}

.card:hover .icons, .item > a:focus .icons {
  font-size: 3.4rem;
}

.icons {
  font-size: 3rem;
  transition: all 0.3s;
}

.gold {
  color: $theme-quaternary;
}
</style>

<script>
import nav from '../../containers/nav'

export default {
  name: 'Home',
  computed: {
    navItems () {
      return nav.items;
    },
  },
}
</script>
