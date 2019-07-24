<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm="8" md="6" lg="5">
        <LoadingContainer :loading="isBusy(sides.debet)">
          <ListGroup
            :categories="categories"
            :isBusy="isBusy(sides.debet)"
            :side="sides.debet"
            @order="onOrder"
            @delete="onDelete"
            @edit="onEdit"
            @new="onNew" />
        </LoadingContainer>
      </b-col>

      <b-col sm="8" md="6" lg="5">
        <LoadingContainer :loading="isBusy(sides.credit)">
          <ListGroup
            :categories="categories"
            :isBusy="isBusy(sides.credit)"
            :side="sides.credit"
            @order="onOrder"
            @delete="onDelete"
            @edit="onEdit"
            @new="onNew" />
        </LoadingContainer>
      </b-col>

      <EditModal
        :show.sync="showEditModal"
        :side="activeSide"
        :category="category"
        @ready="save" />
      
      <DeleteModal
        :show.sync="showDeleteModal"
        :category="category"
        @delete="remove" />
    </b-row>
  </div>
</template>

<script>
import EditModal from './partials/EditModal'
import DeleteModal from './partials/DeleteModal'
import ListGroup from './partials/ListGroup'
import { SIDES } from '@/mijn-ee/globals/constants.js'
import LoadingContainer from '@/mijn-ee/partials/loading/Container'
import * as CategoryService from '@/mijn-ee/services/CategoryService'

export default {
  name: 'Categories',
  components: { EditModal, DeleteModal, ListGroup, LoadingContainer },
  data () {
    return {
      categories: [],
      sides: SIDES,
      category: null,
      activeSide: null,
      showEditModal: false,
      showDeleteModal: false,
      isBusyDebet: false,
      isBusyCredit: false,
    }
  },
  created () {
    this.getCategories();
  },
  methods: {
    onNew (newSide) {
      this.activeSide = newSide;
      this.category = null;
      this.showEditModal = true;
    },
    onEdit (category) {
      this.activeSide = category.side;
      this.category = category;
      this.showEditModal = true;
    },
    onDelete (category) {
      this.category = category;
      this.showDeleteModal = true;
    },
    onOrder (event) {
      if (event && event.moved && event.moved.element) {
        this.orderCategories(event.moved.element.side);
      }
    },
    save (category) {
      if (category.id) {
        this.updateCategory(category);
      } else {
        this.createCategory(category);
      }
    },
    remove (category) {
      this.deleteCategory(category);
    },
    getCategories () {
      this.isBusy(SIDES.debet, true);
      this.isBusy(SIDES.credit, true);

      this.$subscribeTo(
        CategoryService.getCategories(),
        data => {
          this.categories = data;
          this.isBusy(SIDES.debet, false);
          this.isBusy(SIDES.credit, false);
        },
        e => {
          this.ee_errorHandler(e);
          this.isBusy(SIDES.debet, false);
          this.isBusy(SIDES.credit, false);
        }
      );
    },
    orderCategories (side) {
      this.isBusy(side, true);
      const categories = this.categories.filter(category => category.side.code === side.code);

      this.$subscribeTo(
        CategoryService.orderCategories(categories),
        () => {
          this.ee_showAlert('defaultSuccess');
          this.isBusy(side, false);
        },
        e => {
          this.ee_errorHandler(e);
          this.isBusy(side, false);
        }
      );
    },
    updateCategory (category) {
      this.isBusy(category.side, true);

      this.$subscribeTo(
        CategoryService.updateCategory(category.id, category),
        res => {
          const index = this.categories.findIndex(item => item.id === category.id);

          if (index > -1 && res.data) {
            this.categories.splice(index, 1, res.data);
          }

          this.ee_showAlert('defaultSuccess');
          this.$store.dispatch('fetchFiltersData', this);
          this.isBusy(category.side, false);
        },
        e => {
          this.ee_errorHandler(e);
          this.isBusy(category.side, false);
        }
      );
    },
    createCategory (category) {
      this.isBusy(category.side, true);

      this.$subscribeTo(
        CategoryService.createCategory(category),
        res => {
          this.categories.push(res.data);
          this.ee_showAlert('defaultSuccess');
          this.$store.dispatch('fetchFiltersData', this);
          this.isBusy(category.side, false);
        },
        e => {
          this.ee_errorHandler(e);
          this.isBusy(category.side, false);
        }
      );
    },
    deleteCategory (category) {
      this.isBusy(category.side, true);

      this.$subscribeTo(
        CategoryService.deleteCategory(category.id),
        () => {
          this.categories = this.categories.filter(item => item.id !== category.id);
          this.ee_showAlert('categoryDeleted');
          this.$store.dispatch('fetchFiltersData', this);
          this.isBusy(category.side, false);
        },
        e => {
          this.ee_errorHandler(e);
          this.isBusy(category.side, false);
        }
      )
    },
    isBusy (side, value) {
      if (typeof value !== 'undefined') {
        // Setter
        value = Boolean(value);
        if (side.code === SIDES.debet.code) this.isBusyDebet = value;
        if (side.code === SIDES.credit.code) this.isBusyCredit = value;
        return value;
      } else {
        // Getter
        if (side.code === SIDES.debet.code) return this.isBusyDebet;
        if (side.code === SIDES.credit.code) return this.isBusyCredit;
      }
    },
  },
}
</script>
