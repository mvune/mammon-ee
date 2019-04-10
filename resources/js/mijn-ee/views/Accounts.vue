<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm="12">
        <b-button @click="showAddModal = true" type="button" variant="primary" class="mb-3">
          <i aria-hidden="true" class="icon-plus"></i>
          Toevoegen
        </b-button>

        <b-modal
          @ok="onAdd"
          @shown="onAddModalShown"
          v-model="showAddModal"
          title="Rekening toevoegen"
          cancel-title="Annuleer"
          ok-title="Voeg toe"
          centered lazy
          size="sm"
          ref="addModal"
        >
          <b-form @submit.prevent="add()" novalidate>
            <b-form-group>
              <b-form-input v-model.trim="form.name" type="text" placeholder="Kies een naam" />
              <FormFieldError :form="$v.form" :field="'name'" />
            </b-form-group>

            <b-form-group>
              <b-form-input v-model.trim="form.iban" type="text" placeholder="IBAN" />
              <FormFieldError :form="$v.form" :field="'iban'" />
            </b-form-group>

            <b-button type="submit" style="display: none;"></b-button>
          </b-form>
        </b-modal>
      </b-col>

      <b-col md="10" lg="8" class="ee-spinner-container">
        <b-table :items="items" :fields="fields" :busy.sync="isBusy" show-empty>

          <template slot="name" slot-scope="row">

            <template v-if="row.item.isEditing">
              <b-form @submit.prevent="onUpdate(row)" id="accForm" novalidate></b-form>

              <b-form-input form="accForm" v-model="form.name" />
              <FormFieldError :form="$v.form" :field="'name'" />
            </template>

            <template v-if="!row.item.isEditing">
              {{ row.value }}
            </template>

          </template>

          <template slot="iban" slot-scope="row">

            <template v-if="row.item.isEditing">
              <b-form-input form="accForm" v-model="form.iban" />
              <FormFieldError :form="$v.form" :field="'iban'" />
            </template>

            <template v-if="!row.item.isEditing">
              {{ row.value }}
            </template>

          </template>

          <template slot="edit" slot-scope="row">
            <template v-if="row.item.isEditing">
              <button type="submit" form="accForm" aria-label="Opslaan" class="action-button">
                <i aria-hidden="true" class="icon-check text-primary"></i>
              </button>
              <button type="button" aria-label="Annuleren" @click="toggleForm(row)" class="action-button">
                <i aria-hidden="true" class="icon-close text-danger"></i>
              </button>
            </template>

            <template v-if="!row.item.isEditing">
              <button type="button" aria-label="Verwijderen" @click="row.item.showDeleteModal = true" class="action-button">
                <i aria-hidden="true" class="icon-trash text-danger"></i>
              </button>
              <button type="button" aria-label="Wijzigen" @click="toggleForm(row)" class="action-button">
                <i aria-hidden="true" class="icon-pencil text-primary"></i>
              </button>

              <b-modal
                @ok="onDelete(row)"
                v-model="row.item.showDeleteModal"
                title="Rekening verwijderen"
                cancel-title="Annuleer"
                ok-title="Verwijder"
                ok-variant="danger"
                centered lazy
              >
                <p>Weet u zeker dat u rekening <strong>{{ row.item.name }} ({{ row.item.iban }})</strong> wilt verwijderen?</p>
              </b-modal>
            </template>
          </template>

          <template v-if="items.length == 0" v-slot:table-busy>
            <div class="d-flex justify-content-center">
              <b-spinner small variant="primary" label="Laden..."></b-spinner>
            </div>
          </template>

          <template v-slot:empty>
            Geen rekeningen.
          </template>

        </b-table>

        <LoadingSpinner :loading="isBusy && items.length > 0" />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import FormFieldError from '@/mijn-ee/partials/FormFieldError'
import LoadingSpinner from '@/mijn-ee/partials/LoadingSpinner'

export default {
  name: 'Accounts',
  components: { FormFieldError, LoadingSpinner },
  data () {
    return {
      fields: [
        { key: 'name', label: 'Naam', sortable: true, thClass: 'hover-highlight', tdClass: this.getTdClass },
        { key: 'iban', label: 'IBAN', sortable: true, thClass: 'hover-highlight', tdClass: this.getTdClass },
        { key: 'saldo', label: 'Saldo', sortable: true, thClass: 'hover-highlight' },
        { key: 'edit', label: '', tdClass: 'action-buttons-column' },
      ],
      items: [],
      form: {
        name: '',
        iban: '',
      },
      isBusy: false,
      showAddModal: false,
    }
  },
  validations: {
    form: {
      name: { required },
      iban: { required },
    }
  },
  mounted () {
    this.getItems();
  },
  methods: {
    getItems () {
      this.isBusy = true;

      return axios.get('accounts')
        .then(response => this.items = this.formatItems(response.data))
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    onAdd (e) {
      e.preventDefault();
      this.$v.form.$touch();

      if (!this.$v.form.$invalid) {
        this.add();
      }
    },
    add () {
      this.isBusy = true;
      this.$nextTick(() => this.$refs.addModal.hide());

      axios.post('accounts', this.form)
        .then(response => {
          this.getItems().then(() => {
            this.ee_showAlert('defaultSuccess');
          });
        })
        .catch(e => {
          this.ee_errorHandler(e);
          this.isBusy = false;
        })
        .then(() => {
          this.resetForm();
        });
    },
    onUpdate (row) {
      this.$v.form.$touch();

      if (!this.$v.form.$invalid) {
        this.update(row);
      }
    },
    update (row) {
      if (row.item.isEditing) {
        this.isBusy = true;
        const data = {
          id: row.item.id,
          name: this.form.name,
          iban: this.form.iban,
        };

        axios.put(`accounts/${row.item.id}`, data)
          .then(response => {
            row.item.name = this.form.name;
            row.item.iban = this.form.iban;
            this.toggleForm(row);
            this.ee_showAlert('defaultSuccess');
          })
          .catch(this.ee_errorHandler)
          .then(() => this.isBusy = false);
      }
    },
    onDelete (row) {
      this.isBusy = true;

      axios.delete(`accounts/${row.item.id}`)
        .then(response => {
          this.items = this.items.filter(item => item.id !== row.item.id);
          this.ee_showAlert('bankAccountDeleted');
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    toggleForm (row) {
      this.toggleIsEditing(row);
      this.setFormData(row);
      this.focusInputField(row);
    },
    toggleIsEditing (row) {
      const newValue = !row.item.isEditing;
      this.toggleAllIsEditingOff();
      row.item.isEditing = newValue;
    },
    toggleAllIsEditingOff () {
      for (let item of this.items) {
        item.isEditing = false;
      }
    },
    setFormData (row) {
      if (row.item.isEditing) {
        this.form.name = row.item.name;
        this.form.iban = row.item.iban;
      } else {
        this.form.name = '';
        this.form.iban = '';
      }
    },
    focusInputField (row) {
      if (row.item.isEditing) {
        this.$nextTick(() => this.$el.querySelector('input').select());
      }
    },
    formatItems (items) {
      for (let item of items) {
        item.saldo = '€ 0,00';
        item.isEditing = false;
        item.showDeleteModal = false;
      }

      return items;
    },
    onAddModalShown () {
      this.toggleAllIsEditingOff();
      this.resetForm();
      setTimeout(() => this.$el.querySelector('input').focus(), 0);
    },
    resetForm () {
      this.form.name = '';
      this.form.iban = '';
      this.$v.form.$reset();
    },
    getTdClass (value, column, row) {
      let tdClass = 'is-editable';

      if (row.isEditing) {
        tdClass += ' is-editing';
      }

      return tdClass;
    },
  }
}
</script>
