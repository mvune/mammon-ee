<template>
  <div class="text-danger">
    <!-- <span v-if="!form[field].$dirty || !form[field].$error">&nbsp;</span> -->
    
    <template v-if="form[field].$dirty">

      <span v-if="form[field].required === false">Dit veld is verplicht</span>

      <span v-else-if="form[field].email === false">E-mailadres is ongeldig</span>

      <span v-else-if="form[field].minLength === false">Moet minimaal {{ form[field].$params.minLength.min }} tekens lang zijn</span>

      <template v-else-if="field === 'passwordRepeat'">
        <span v-if="form[field].required && !form[field].sameAs">Dit veld komt niet overeen met het nieuwe wachtwoord</span>
      </template>

      <span v-else-if="form[field].isCsv === false">Bestand moet een .csv-bestand zijn</span>

    </template>
  </div>
</template>

<script>
export default {
  name: 'FormFieldError',
  props: [ 'form', 'field' ],
}
</script>
