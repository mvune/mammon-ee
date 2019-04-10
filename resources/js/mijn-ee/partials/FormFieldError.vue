<template>
  <div class="text-danger">
    <!-- <span v-if="!form[field].$dirty || !form[field].$error">&nbsp;</span> -->
    
    <template v-if="form[field].$dirty">

      <span v-if="form[field].required === false">Dit veld is verplicht</span>

      <span v-if="form[field].email === false">E-mailadres is ongeldig</span>

      <span v-if="form[field].minLength === false">Moet minimaal {{ form[field].$params.minLength.min }} tekens lang zijn</span>

      <template v-else-if="field === 'passwordRepeat'">
        <span v-if="form[field].required && !form[field].sameAs">Dit veld komt niet overeen met het nieuwe wachtwoord</span>
      </template>

    </template>
  </div>
</template>

<script>
import { required, email, minLength, sameAs } from 'vuelidate/lib/validators'

export default {
  name: 'FormFieldError',
  props: [ 'form', 'field' ],
}
</script>
