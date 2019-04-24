<template>
  <div class="animated fadeIn">

    <b-row>
      <b-col xs="11" sm="9" md="7" lg="5" class="ee-spinner-container">
        <b-form-group>
          <b-form-radio-group v-model="form.bank">
            <b-form-radio value="1">Rabobank</b-form-radio>
          </b-form-radio-group>
          <FormFieldError :form="$v.form" :field="'bank'" />
        </b-form-group>

        <b-form-group>
          <b-form-file
            v-model="form.file"
            id="transactions-form-file"
            form="transactions-form"
            accept="text/csv"
            :state="formFileState"
            placeholder="Kies een .csv-bestand..."
            drop-placeholder="Drop .csv-bestand hier..."
            browse-text="Bladeren..."
          ></b-form-file>
          <FormFieldError :form="$v.form" :field="'file'" />
        </b-form-group>

        <b-form @submit.prevent="onSubmit()" id="transactions-form" novalidate>
          <b-button type="submit" variant="primary">
            Uploaden
          </b-button>

          <a v-if="form.file" @click.prevent="resetForm()" href="" class="prim-link ml-2">
            Leegmaken
          </a>
        </b-form>

        <LoadingSpinner :loading="isBusy" />
        <LoadingFaderer :loading="isBusy" />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import { required, helpers } from 'vuelidate/lib/validators'
import FormFieldError from '@/mijn-ee/partials/FormFieldError'
import LoadingSpinner from '@/mijn-ee/partials/LoadingSpinner'
import LoadingFaderer from '@/mijn-ee/partials/LoadingFaderer'

const isCsv = (file) => file && file.name ? file.name.endsWith('.csv') : false;
const maxFileSize = (value) => {
  return helpers.withParams(
    { max: value },
    (file) => file && file.size ? file.size < value : false
  );
};

export default {
  name: 'Transactions',
  components: { FormFieldError, LoadingSpinner, LoadingFaderer },
  data () {
    return {
      isBusy: false,
      form: {
        bank: 1,
        file: null,
      },
    }
  },
  validations: {
    form: {
      bank: { required },
      file: { required, isCsv, maxFileSize: maxFileSize(12000000) }, // Max = 12MB
    }
  },
  computed: {
    formFileState () {
      return this.form.file ? !this.$v.form.file.$invalid : null;
    }
  },
  methods: {
    onSubmit () {
      this.$v.form.$touch();

      if (!this.$v.form.$invalid) {
        this.isBusy = true;
        const formData = new FormData();
        formData.append('bank', this.form.bank);
        formData.append('csv_file', this.form.file);
        
        axios.post('transactions', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
          .then(response => {
            this.ee_showAlert('defaultSuccess');
          })
          .catch(this.ee_errorHandler)
          .then(() => this.isBusy = false);
      }
    },
    resetForm () {
      this.form.file = null;
      this.$v.form.$reset();
    }
  },
}
</script>
