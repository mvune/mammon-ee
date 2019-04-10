<template>
  <div class="animated fadeIn">

    <b-row>
      <b-col xs="11" sm="9" md="7" lg="5">
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
      </b-col>
    </b-row>

    <b-row>
      <b-col xs="11" sm="9" md="7" lg="5">
        <b-form @submit.prevent="onSubmit()" id="transactions-form" novalidate>
          <b-button type="submit" variant="primary" :disabled="form.file === null">
            Uploaden
          </b-button>

          <a v-if="form.file" @click.prevent="resetForm()" href="" class="prim-link ml-2">
            Annuleren
          </a>
        </b-form>
      </b-col>
    </b-row>

  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import FormFieldError from '@/mijn-ee/partials/FormFieldError'

const isCsv = (file) => file && file.name ? file.name.endsWith('.csv') : false;

export default {
  name: 'Transactions',
  components: { FormFieldError },
  data () {
    return {
      form: {
        file: null,
      },
    }
  },
  validations: {
    form: {
      file: { required, isCsv },
    }
  },
  computed: {
    formFileState () {
      return this.form.file ? isCsv(this.form.file) : null;
    }
  },
  methods: {
    onSubmit () {
      this.$v.form.$touch();

      if (!this.$v.form.$invalid) {
        
      }
    },
    resetForm () {
      this.form.file = null;
      this.$v.form.$reset();
    }
  },
}
</script>
