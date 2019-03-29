<template>
  <div class="text-danger">
    <span v-if="!form[field].$dirty || !form[field].$error">&nbsp;</span>
    
    <template v-if="form[field].$dirty">

      <span v-if="!form[field].required">Dit veld is verplicht</span>

      <template v-else-if="field === 'email'">
        <span v-if="!form[field].email">E-mailadres is ongeldig</span>
      </template>

      <template v-else-if="field === 'passwordNew'">
        <span v-if="!form[field].minLength">Wachtwoord moetminimaal 8 tekens lang zijn</span>
      </template>

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
