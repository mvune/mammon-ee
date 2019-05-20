<template>
  <b-card no-body>
    <b-list-group>
      <!-- Header -->
      <b-list-group-item v-if="side === 'debet'" variant="success">
        Inkomsten
        <i class="fa fa-arrow-up fa-lg mt-1 float-right"></i>
      </b-list-group-item>

      <b-list-group-item v-if="side === 'credit'" variant="danger">
        Uitgaven
        <i class="fa fa-arrow-down fa-lg mt-1 float-right"></i>
      </b-list-group-item>

      <!-- Items -->
      <template v-for="category in categories">
        <b-list-group-item
          v-if="category.side === side"
          v-b-modal="'edit-modal-' + category.id"
          :key="category.id"
          @click="$emit('edit', category)"
          button
        >
          {{ category.name }}
          <!-- Delete button -->
          <button
            type="button"
            v-b-modal="'delete-modal-' + category.id"
            title="Verwijderen"
            class="action-button delete-button float-right"
            @click="$emit('delete', category) && $event.stopPropagation()"
          >
            <i aria-hidden="true" class="icon-trash text-danger"></i>
          </button>
        </b-list-group-item>
      </template>

      <!-- New button -->
      <b-list-group-item>
        <b-button @click="$emit('new', side)" type="button" variant="primary">
          <i aria-hidden="true" class="icon-plus"></i>
          Nieuw
        </b-button>
      </b-list-group-item>
    </b-list-group>
  </b-card>
</template>

<style lang="scss" scoped>
.delete-button {
  margin-right: -0.75rem;
}
</style>

<script>
export default {
  name: 'CategoriesListGroup',
  props: {
    categories: Array,
    side: String,
  },
}
</script>
