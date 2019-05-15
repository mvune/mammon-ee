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
          <b-form @submit.prevent="onAdd($event)" novalidate>
            <b-form-group>
              <b-form-input v-model.trim="form.name" type="text" placeholder="Kies een naam" />
              <FormFieldError :form="$v.form" :field="'name'" />
            </b-form-group>

            <b-form-group>
              <b-form-input v-model.trim="form.iban" type="text" class="uppercase" placeholder="IBAN" />
              <FormFieldError :form="$v.form" :field="'iban'" />
            </b-form-group>

            <b-button type="submit" style="display: none;"></b-button>
          </b-form>
        </b-modal>
      </b-col>

      <b-col md="9" class="ee-spinner-container">
        <fieldset :disabled="isBusy">
          <b-table :items="items" :fields="fields" :busy.sync="isBusy" show-empty>

            <template slot="iban" slot-scope="row">

              <template v-if="row.item.isEditing">
                <b-form-input v-model="form.iban" class="uppercase" @keydown.enter="onUpdate(row, $event)" />
                <FormFieldError :form="$v.form" :field="'iban'" />
              </template>

              <template v-if="!row.item.isEditing">
                {{ row.value | ee_iban }}
              </template>

            </template>

            <template slot="name" slot-scope="row">

              <template v-if="row.item.isEditing">
                <b-form-input v-model="form.name" @keydown.enter="onUpdate(row, $event)" />
                <FormFieldError :form="$v.form" :field="'name'" />
              </template>

              <template v-if="!row.item.isEditing">
                {{ row.value || '-' }}
              </template>

            </template>

            <template slot="balance" slot-scope="row">
              <span v-if="row.item.balance === null">-</span>
              <span v-else v-b-tooltip.hover :title="$options.filters.ee_date(row.item.balance_date)">
                {{ row.value | ee_valuta }}
              </span>
            </template>

            <template slot="edit" slot-scope="row">
              <template v-if="row.item.isEditing">
                <button type="button" title="Opslaan" @click="onUpdate(row)" class="action-button">
                  <i aria-hidden="true" class="icon-check text-primary"></i>
                </button>
                <button type="button" title="Annuleren" @click="toggleForm(row)" class="action-button">
                  <i aria-hidden="true" class="icon-close text-danger"></i>
                </button>
              </template>

              <template v-if="!row.item.isEditing">
                <button type="button" title="Verwijderen" v-b-modal="'modal' + row.item.id" class="action-button">
                  <i aria-hidden="true" class="icon-trash text-danger"></i>
                </button>
                <button type="button" title="Wijzigen" @click="toggleForm(row)" class="action-button">
                  <i aria-hidden="true" class="icon-pencil text-primary"></i>
                </button>

                <b-modal
                  @ok="onDelete(row)"
                  :id="'modal' + row.item.id"
                  title="Rekening verwijderen"
                  cancel-title="Annuleer"
                  ok-title="Verwijder"
                  ok-variant="danger"
                  centered lazy
                >
                  <p>
                    Weet u zeker dat u rekening
                    <strong v-if="row.item.name">{{ row.item.name }} ({{ row.item.iban | ee_iban }})</strong>
                    <strong v-if="!row.item.name">{{ row.item.iban | ee_iban }}</strong>
                    wilt verwijderen?
                  </p>
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
        </fieldset>

        <LoadingSpinner :loading="isBusy && items.length > 0" />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'
import FormFieldError from '@/mijn-ee/partials/FormFieldError'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import * as AccountService from '@/mijn-ee/services/AccountService'

export default {
  name: 'Accounts',
  components: { FormFieldError, LoadingSpinner },
  data () {
    return {
      fields: [
        { key: 'iban', label: 'Rekening', sortable: true, thClass: 'hover-highlight', tdClass: this.getTdClass },
        { key: 'name', label: 'Alias', sortable: true, thClass: 'hover-highlight', tdClass: this.getTdClass },
        { key: 'balance', label: 'Saldo', sortable: true, thClass: 'hover-highlight' },
        { key: 'edit', label: '', tdClass: 'action-buttons-column' },
      ],
      items: [], // Accounts
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
      name: { maxLength: maxLength(100) },
      iban: { required, maxLength: maxLength(34) },
    }
  },
  mounted () {
    this.getItems();
  },
  methods: {
    getItems () {
      this.isBusy = true;

      this.$subscribeTo(
        AccountService.getAccounts(),
        data => {
          this.items = data;
          this.formatItems();
        },
        this.ee_errorHandler,
        () => this.isBusy = false
      );
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

      AccountService.createAccount(this.form)
        .subscribe((response) => {
          this.items.push(this.formatItem(response.data));
          this.ee_showAlert('defaultSuccess');
        },
        this.ee_errorHandler,
        () => {
          this.resetForm();
          this.isBusy = false;
        });
    },
    onUpdate (row, event) {
      this.$v.form.$touch();

      if (!this.$v.form.$invalid) {
        event && event.target.blur();
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

        AccountService.updateAccount(row.item.id, data)
          .subscribe(() => {
            this.formatIban();
            row.item.name = this.form.name;
            row.item.iban = this.form.iban;
            this.toggleForm(row);
            this.ee_showAlert('defaultSuccess');
          },
          this.ee_errorHandler,
          () => this.isBusy = false);
      }
    },
    onDelete (row) {
      this.isBusy = true;

      AccountService.deleteAccount(row.item.id)
        .subscribe(() => {
          this.items = this.items.filter(item => item.id !== row.item.id);
          this.ee_showAlert('accountDeleted');
        },
        this.ee_errorHandler,
        () => this.isBusy = false);
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
    formatItems () {
      for (let item of this.items) {
        this.formatItem(item);
      }
    },
    formatItem (item) {
      item.isEditing = false;
      return item;
    },
    formatIban () {
      this.form.iban = this.form.iban.replace(/\s/g, '').toUpperCase();
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
