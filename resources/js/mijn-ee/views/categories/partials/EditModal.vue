<template>
  <b-modal
    @ok.prevent="onOk"
    @shown="onShown"
    @hidden="$emit('update:show', false)"
    :visible="show"
    :title="title"
    cancel-title="Annuleer"
    :ok-title="okTitle"
    centered lazy
    ref="editModal"
    :id="category ? 'edit-modal-' + category.id : ''"
  >
    <b-form @submit.prevent="onOk" novalidate>
      <b-form-group label="Naam" label-for="name" label-cols="2">
        <b-form-input v-model.trim="form.name" type="text" id="name" />
        <FormFieldError :form="$v.form" :field="'name'" />
      </b-form-group>

      <h5 class="modal-title text-danger mb-1">Regels</h5>

      <b-row class="mb-1">
        <b-col sm="5">
          <span id="field-label">Veld</span>
        </b-col>
        <b-col sm="7">
          <span id="value-label">Waarde</span>
        </b-col>
      </b-row>

      <b-row v-for="(v, index) in $v.form.rules.$each.$iter" :key="index">
        <b-col sm="5">
          <b-form-group>
            <b-form-select
              v-model="form.rules[index].target_id"
              :options="filterTargets"
              aria-labelledby="field-label"
            ></b-form-select>
            <FormFieldError :form="v" :field="'target_id'" />
          </b-form-group>
        </b-col>

        <b-col cols="11" sm="6">
          <b-form-group>
            <b-form-input
              v-model.trim="form.rules[index].expression"
              type="text"
              aria-labelledby="value-label" />
            <FormFieldError :form="v" :field="'expression'" />
          </b-form-group>
        </b-col>

        <b-col cols="1">
          <button
            type="button"
            title="Verwijderen"
            class="action-button delete-button"
            :disabled="oneRuleLeft"
            @click="deleteRule(index)"
          >
            <i aria-hidden="true" :class="[ oneRuleLeft ? 'disabled' : 'text-danger' ]" class="icon-trash"></i>
          </button>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <b-button @click="addRule()" type="button" variant="primary">
            <i aria-hidden="true" class="icon-plus"></i>
            Nieuw
          </b-button>
        </b-col>
      </b-row>

      <b-button type="submit" style="display: none;"></b-button>
    </b-form>
  </b-modal>
</template>

<style lang="scss" scoped>
.delete-button {
  margin: 0.5rem 0.5rem 0.5rem -1.25rem;
}
</style>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'
import FormFieldError from '@/mijn-ee/partials/FormFieldError'

const inSides = value => ['debet', 'credit'].indexOf(value) !== -1;

export default {
  name: 'CategoriesEditModal',
  components: { FormFieldError },
  props: {
    category: Object,
    show: Boolean,
    side: { validator: value => inSides(value) || value === '' },
  },
  data () {
    return {
      filterTargets: [],
      form: {
        name: '',
        rules: [
          {
            target_id: '',
            expression: '',
          },
        ],
      },
      isBusy: false,
    }
  },
  validations: {
    form: {
      name: { required, maxLength: maxLength(100) },
      rules: {
        required,
        $each: {
          target_id: { required },
          expression: { required },
        },
      }
    }
  },
  computed: {
    title () {
      let title = this.category ? 'Categorie bewerken' : 'Categorie toevoegen';
      title += this.side === 'credit' ? ' - Uitgaven' : ' - Inkomsten';
      return title;
    },
    okTitle () {
      return this.category ? 'Wijzig' : 'Voeg toe';
    },
    oneRuleLeft () {
      return this.form.rules.length === 1;
    },
  },
  created () {
    this.getFilterTargets();
  },
  methods: {
    getFilterTargets () {
      this.isBusy = true;

      axios.get('transaction-filter-targets')
        .then(res => {
          this.filterTargets = this.formatFilterTargetsData(res.data);
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    formatFilterTargetsData (data) {
      return data.map(item => ({ value: item.id, text: item.label }));
    },
    onOk () {
      this.$v.form.$touch();

      if (!this.$v.form.$invalid) {
        this.$nextTick(() => this.$refs.editModal.hide());
        this.$emit('ready', this.categoryFromFormData());
      }
    },
    onShown () {
      this.formDataFromCategory();
      setTimeout(() => this.$el.querySelector('input').focus(), 0);
    },
    addRule () {
      this.form.rules.push({
        target_id: '',
        expression: '',
      });
    },
    deleteRule (index) {
      this.form.rules.splice(index, 1);
    },
    categoryFromFormData () {
      const category = _.clone(this.category) || {};
      category.name = this.form.name;
      category.rules = this.form.rules;
      category.side = category.side || this.side;
      category.sequence = category.sequence || 99;
      return category;
    },
    formDataFromCategory () {
      this.resetForm();

      if (this.category) {
        this.form.name = this.category.name;
        this.form.rules = this.category.rules;
      }
    },
    resetForm () {
      this.form.name = '';
      this.form.rules = [];
      this.addRule();
      this.$v.form.$reset();
    },
  },
}
</script>
