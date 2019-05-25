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
      <draggable :list="categories" handle=".handle" @change="$emit('order', $event)">
        <template v-for="category in categories">
          <b-list-group-item
            v-if="category.side === side"
            v-b-modal="'edit-modal-' + category.id"
            :key="category.id"
            @click="$emit('edit', category)"
            button
          >
            {{ category.name }}
            <div class="btn-container float-right">
              <!-- Move button -->
              <button
                type="button"
                title="Rangschikken"
                class="action-button move-button handle"
                @click.prevent="$event.stopPropagation()"
              >
                <i aria-label="Rangschikken" class="icon-menu text-primary"></i>
              </button>
              <!-- Delete button -->
              <button
                type="button"
                title="Verwijderen"
                class="action-button delete-button"
                v-b-modal="'delete-modal-' + category.id"
                @click="$emit('delete', category) && $event.stopPropagation()"
              >
                <i aria-label="Verwijderen" class="icon-trash text-danger"></i>
              </button>
            </div>
          </b-list-group-item>
        </template>
      </draggable>

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
.btn-container {
  margin-right: -0.75rem;
}

.move-button {
  cursor: move;
}
</style>

<script>
import draggable from 'vuedraggable'

export default {
  name: 'CategoriesListGroup',
  components: { draggable },
  props: {
    categories: Array,
    side: String,
  },
}
</script>
