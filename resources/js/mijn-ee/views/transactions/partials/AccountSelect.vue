<template>

  <LoadingContainer :loading="isBusy">
    <b-form-group>
      <label class="prim-head-sm">Accounts</label>
      <b-form-checkbox
        v-model="allSelected"
        :indeterminate="indeterminate"
        aria-describedby="accounts"
        aria-controls="accounts"
        @change="toggleAll"
      >
        Alle
      </b-form-checkbox>

      <b-form-checkbox-group
        id="checkbox-group-accounts"
        v-model="selected"
        name="accounts"
        class="ml-4"
        aria-label="Individuele accounts"
        stacked
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
  </LoadingContainer>

</template>

<script>
import LoadingContainer from '@/mijn-ee/partials/loading/Container'

export default {
  name: 'AccountSelect',
  components: { LoadingContainer },
  data () {
    return {
      isBusy: false,
      accounts: [],
      selected: [],
      allSelected: true,
      indeterminate: false,
    }
  },
  mounted () {
    this.getAccounts();
  },
  methods: {
    getAccounts () {
      this.isBusy = true;

      return axios.get('accounts')
        .then(response => {
          this.accounts = response.data;
          this.selected = this.allSelectedArray();
          this.allSelected = true;
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    allSelectedArray () {
      return this.accounts.map(item => item.id);
    },
    toggleAll (checked) {
      this.selected = checked ? this.allSelectedArray() : [];
    },
  },
  watch: {
    selected (newVal, oldVal) {
      // Handle changes in individual account checkboxes
      if (newVal.length === 0) {
        this.indeterminate = false
        this.allSelected = false
      } else if (newVal.length === this.accounts.length) {
        this.indeterminate = false
        this.allSelected = true
      } else {
        this.indeterminate = true
        this.allSelected = false
      }

      this.$emit('update:selected', this.selected);
    }
  }
}
</script>
