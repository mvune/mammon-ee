<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm="6" md="5" lg="4" class="ee-spinner-container">
        <b-form @submit.prevent="onSubmit()" novalidate>
          <b-form-group>
            <label for="name">Naam</label>
            <b-form-input v-model.trim="form.name" type="text" id="name" />
            <FormFieldError :form="$v.form" :field="'name'" />
          </b-form-group>

          <b-form-group>
            <label for="email">E-mailadres</label>
            <b-form-input v-model.trim="form.email" type="email" id="email" />
            <FormFieldError :form="$v.form" :field="'email'" />
          </b-form-group>

          <b-form-group>
            <span class="d-flex justify-content-between">
              <label for="password">Wachtwoord</label>
              <a
                @click.prevent="changePassword = !changePassword"
                href=""
                class="prim-link"
              >
                Wijzigen?
              </a>
            </span>
            <b-form-input v-model="form.password" type="password" id="password" />
            <FormFieldError :form="$v.form" :field="'password'" />
          </b-form-group>

          <b-collapse id="collapse-ww" v-model="changePassword">
            <b-form-group>
              <label for="password">Nieuwe wachtwoord</label>
              <b-input-group>
                <b-input-group-text slot="append"><i class="fa fa-lock" aria-hidden="true"></i></b-input-group-text>
                <b-form-input v-model="form.passwordNew" type="password" id="password-new" />
              </b-input-group>
              <FormFieldError :form="$v.form" :field="'passwordNew'" />
            </b-form-group>

            <b-form-group>
              <label for="password">Herhaal wachtwoord</label>
              <b-input-group>
                <b-input-group-text slot="append"><i class="fa fa-lock" aria-hidden="true"></i></b-input-group-text>
                <b-form-input v-model="form.passwordRepeat" type="password" id="password-repeat" />
              </b-input-group>
              <FormFieldError :form="$v.form" :field="'passwordRepeat'" />
            </b-form-group>
          </b-collapse>

          <b-button type="submit" variant="primary" class="mt-2">Opslaan</b-button>
        </b-form>

        <LoadingSpinner :loading="isBusy" />
        <LoadingFaderer :loading="isBusy" />
      </b-col>

      <b-col sm="6" md="5" lg="4" offset-md="1" offset-lg="2">
        <div class="danger-box">
          <h3>Account verwijderen</h3>
          <p>Verwijder uw account en alle bijbehorende gegevens.</p>
          <button @click="showDeleteModal = true" class="btn btn-danger">Verwijderen</button>
        </div>
      </b-col>
    </b-row>

    <b-modal
      @ok="onDelete()"
      v-model="showDeleteModal"
      title="Account verwijderen"
      cancel-title="Annuleer"
      ok-title="Verwijder account"
      ok-variant="danger"
      centered lazy
    >
      <p>Weet u zeker dat u dit account wilt verwijderen?</p>
      <p><strong>Let op!</strong> Deze actie kan niet ongedaan worden gemaakt.</p>
    </b-modal>
    <form id="delete-account-form" action="/destroy" method="POST" style="display: none;">
      <input type="hidden" name="_method" value="DELETE">
      <input type="hidden" name="_token" v-bind:value="token">
    </form>
  </div>
</template>

<style scoped>
form { margin-bottom: 1rem; }
label { margin-bottom: 0.25rem; }
</style>

<script>
import { required, requiredIf, email, minLength, sameAs } from 'vuelidate/lib/validators'
import FormFieldError from '@/mijn-ee/partials/FormFieldError'
import LoadingSpinner from '@/mijn-ee/partials/loading/Spinner'
import LoadingFaderer from '@/mijn-ee/partials/loading/Faderer'

export default {
  name: 'profile',
  components: { FormFieldError, LoadingSpinner, LoadingFaderer },
  data () {
    return {
      changePassword: false,
      showDeleteModal: false,
      isBusy: false,
      form: {
        name: '',
        email: '',
        password: '',
        passwordNew: '',
        passwordRepeat: '',
      },
      token: $('meta[name="csrf-token"]').attr('content'),
    }
  },
  validations: {
    form: {
      name: { required },
      email: { required, email },
      password: { required },
      passwordNew: {
        required: requiredIf(function () { return this.changePassword }),
        minLength: minLength(8),
      },
      passwordRepeat: {
        required: requiredIf(function () { return this.changePassword }),
        sameAs: sameAs('passwordNew'),
      },
    },
  },
  created () {
    this.getUser();
  },
  methods: {
    onSubmit () {
      this.touchFields();

      if (!this.$v.form.$invalid) {
        this.updateUser();
      }
    },
    onDelete () {
      document.getElementById('delete-account-form').submit();
    },
    getUser () {
      this.isBusy = true;

      axios.get('user')
        .then(response => {
          this.form.name = response.data.name;
          this.form.email = response.data.email;
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    updateUser () {
      this.isBusy = true;

      axios.put('user', this.form)
        .then(response => {
          this.ee_showAlert('defaultSuccess');
          this.resetForm();
        })
        .catch(this.ee_errorHandler)
        .then(() => this.isBusy = false);
    },
    touchFields () {
      this.$v.form.name.$touch();
      this.$v.form.email.$touch();
      this.$v.form.password.$touch();

      if (this.changePassword) {
        this.$v.form.passwordNew.$touch();
        this.$v.form.passwordRepeat.$touch();
      } else {
        this.form.passwordNew = '';
        this.form.passwordRepeat = '';
        this.$v.form.passwordNew.$reset();
        this.$v.form.passwordRepeat.$reset();
      }
    },
    resetForm () {
      this.form.password = '';
      this.form.passwordNew = '';
      this.form.passwordRepeat = '';
      this.$v.form.$reset();
      this.changePassword = false;
    }
  }
}
</script>
