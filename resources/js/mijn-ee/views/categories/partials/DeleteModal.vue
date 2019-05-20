<template>
  <b-modal
    @ok="$emit('delete', category)"
    @hidden="$emit('update:show', false)"
    :visible="show"
    :id="category ? 'delete-modal-' + category.id : ''"
    title="Categorie verwijderen"
    cancel-title="Annuleer"
    ok-title="Verwijder"
    ok-variant="danger"
    centered lazy
  >
    <p>
      Weet u zeker dat u categorie <strong v-if="category">{{ category.name }}</strong> wilt verwijderen?
    </p>
  </b-modal>
</template>

<script>
export default {
  name: 'CategoriesDeleteModal',
  props: {
    show: Boolean,
    category: Object,
  },
}
</script>

