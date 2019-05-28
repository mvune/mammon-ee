<template>
  <b-card no-body>
    <b-list-group>
      <!-- Header -->
      <b-list-group-item v-if="side.code === sides.debet.code" variant="success">
        {{ side.label }}
        <i class="fa fa-arrow-up fa-lg mt-1 float-right"></i>
      </b-list-group-item>

      <b-list-group-item v-if="side.code === sides.credit.code" variant="danger">
        {{ side.label }}
        <i class="fa fa-arrow-down fa-lg mt-1 float-right"></i>
      </b-list-group-item>

      <!-- Items -->
      <draggable :list="categories" handle=".handle" @change="$emit('order', $event)">
        <template v-for="category in categories">
          <b-list-group-item
            v-if="category.side.code === side.code"
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

      <b-list-group-item v-if="!sideHasCategories">
        <template v-if="!isBusy">
          Geen categorieën.
        </template>
        &nbsp;
      </b-list-group-item>

      <!-- New button -->
      <b-list-group-item class="primary-btn-list-item">
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

.primary-btn-list-item {
  padding: 0.5rem 1rem;
}

.move-button {
  cursor: move;
}
</style>

<script>
import draggable from 'vuedraggable'
import { SIDES } from '@/mijn-ee/globals/constants.js'

export default {
  name: 'CategoriesListGroup',
  components: { draggable },
  props: {
    categories: Array,
    isBusy: Boolean,
    side: Object,
  },
  data () {
    return {
      sides: SIDES,
    }
  },
  computed: {
    sideHasCategories () {
      return this.categories.some(item => item.side.code === this.side.code);
    }
  },
}
</script>
