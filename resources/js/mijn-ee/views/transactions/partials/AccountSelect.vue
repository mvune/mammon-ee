<template>

  <b-form-group>
    <b-form-checkbox-group
      id="checkbox-group-accounts"
      v-model="selected"
      name="accounts"
      aria-label="Rekeningen"
    >
      <b-form-checkbox v-for="ac of accounts" :key="ac.id" :value="ac.id">
        <template v-if="ac.name">
          {{ ac.name }}
          <br />
          <span class="text-muted under-checkbox">{{ ac.iban | ee_iban }}</span>
        </template>

        <template v-if="!ac.name">
          <span class="text-muted">{{ ac.iban | ee_iban }}</span>
        </template>
      </b-form-checkbox>
    </b-form-checkbox-group>
  </b-form-group>

</template>

<style lang="scss" scoped>
.custom-checkbox {
  width: 46%;
}
</style>

<script>
export default {
  name: 'AccountSelect',
  props: {
    accounts: Array,
  },
  data () {
    return {
      selected: [],
    }
  },
  watch: {
    accounts (newValue) {
      this.selected = newValue.map(item => item.id);
    },
    selected (newValue) {
      this.$emit('selected', newValue);
    },
  },
}
</script>
