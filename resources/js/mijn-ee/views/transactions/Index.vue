<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col md="10" lg="8">
        <b-table
          :items="items"
          :fields="fields"
          :busy.sync="isBusy"
          selectable
          select-mode="single"
          @row-clicked="rowClicked"
          show-empty
        >  
          <template slot="row-details">
            Details
          </template>

          <template v-if="items.length == 0" v-slot:table-busy>
            <div class="d-flex justify-content-center">
              <b-spinner small variant="primary" label="Laden..."></b-spinner>
            </div>
          </template>

          <template v-slot:empty>
            Geen transacties.
          </template>

        </b-table>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'TransactionsIndex',
  data () {
    return {
      isBusy: false,
      fields: [
        { key: 'date', label: 'Datum' },
        { key: 'counterparty_name', label: 'Tegenpartij' },
        { key: 'amount', label: 'Bedrag', formatter: this.$options.filters.valuta },
      ],
      items: [],
    }
  },
  mounted () {
    this.getItems();
  },
  methods: {
    getItems () {
      this.isBusy = true;

      return axios.get('transactions')
        .then(response => {
          this.items = response.data;
          this.formatItems();
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    formatItems () {
      for (let item of this.items) {
        item._showDetails = false;
      }
    },
    rowClicked (row) {
      this.toggleDetails(row);
    },
    toggleDetails (item) {
      const current = item._showDetails;
      this.hideDetailsAll();
      item._showDetails = !current;
    },
    hideDetailsAll () {
      for (let item of this.items) {
        item._showDetails = false;
      }
    },
  }
}
</script>
