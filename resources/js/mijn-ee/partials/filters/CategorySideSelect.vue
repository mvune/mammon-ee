<template>

  <b-form-group>
    <b-form-checkbox
      v-if="categories.length > 0"
      v-model="allSelected"
      :indeterminate="indeterminate"
      aria-describedby="categories"
      aria-controls="categories"
      @change="toggleAll"
    >
      <span class="text-primary">
        {{ side.label }}
      </span>
    </b-form-checkbox>

    <b-form-checkbox-group
      id="checkbox-group-categories"
      v-model="selected"
      name="categories"
      aria-label="Individuele categorieën"
    >
      <b-form-checkbox
        v-for="category of sideCategories"
        :key="category.id"
        :value="category.id"
        class="ml-4"
      >
        {{ category.name }}
      </b-form-checkbox>
    </b-form-checkbox-group>
  </b-form-group>

</template>

<script>
export default {
  name: 'CategorySideSelect',
  props: {
    categories: Array,
    side: {
      type: Object,
      required: true,
    },
  },
  data () {
    return {
      selected: [],
      allSelected: true,
      indeterminate: false,
    }
  },
  computed: {
    sideCategories () {
      return this.categories
        .filter(item => item.side.code === this.side.code)
        .concat(this.makeNoCategory());
    },
  },
  methods: {
    toggleAll (checked) {
      this.selected = checked ? this.allIds() : [];
    },
    allIds () {
      return this.sideCategories.map(item => item.id);
    },
    makeNoCategory () {
      const category = {
        id: 'null:' + this.side.code,
        name: 'Overig',
      };
      return category;
    },
  },
  watch: {
    sideCategories () {
      this.selected = this.allIds();
    },
    selected (newValue) {
      // Handle changes in individual checkboxes.
      if (newValue.length === 0) {
        this.indeterminate = false
        this.allSelected = false
      } else if (newValue.length === this.sideCategories.length) {
        this.indeterminate = false
        this.allSelected = true
      } else {
        this.indeterminate = true
        this.allSelected = false
      }

      this.$emit('selected', this.selected);
    }
  }
}
</script>
