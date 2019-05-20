<template>
  <div class="animated fadeIn">
    <b-row>
      <b-col sm="8" md="6" lg="5">
        <LoadingContainer :loading="isBusy && isDebet()">
          <ListGroup
            :categories="categories"
            :isBusy="isBusy"
            side="debet"
            @delete="onDelete"
            @edit="onEdit"
            @new="onNew" />
        </LoadingContainer>
      </b-col>

      <b-col sm="8" md="6" lg="5">
        <LoadingContainer :loading="isBusy && isCredit()">
          <ListGroup
            :categories="categories"
            :isBusy="isBusy"
            side="credit"
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
import LoadingContainer from '@/mijn-ee/partials/loading/Container'
import * as CategoryService from '@/mijn-ee/services/CategoryService'

export default {
  name: 'CategoriesIndex',
  components: { EditModal, DeleteModal, ListGroup, LoadingContainer },
  data () {
    return {
      categories: [],
      category: null,
      activeSide: '', // 'debet' || 'credit'
      showEditModal: false,
      showDeleteModal: false,
      isBusy: false,
    }
  },
  created () {
    this.getCategories();
  },
  methods: {
    onNew (side) {
      this.activeSide = side;
      this.category = null;
      this.showEditModal = true;
    },
    onEdit (category) {
      this.activeSide = category.side;
      this.category = category;
      this.showEditModal = true;
    },
    onDelete (category) {
      this.activeSide = category.side;
      this.category = category;
      this.showDeleteModal = true;
    },
    save (category) {
      if (category.id) {
        this.updateCategory(category);
      } else {
        this.createCategory(category);
      }
    },
    remove (category) {
      this.deleteCategory(category.id);
    },
    getCategories () {
      this.isBusy = true;

      this.$subscribeTo(
        CategoryService.getCategories(),
        data => this.categories = data,
        this.ee_errorHandler,
        () => this.isBusy = false
      );
    },
    updateCategory (category) {
      this.isBusy = true;

      this.$subscribeTo(
        CategoryService.updateCategory(category.id, category),
        res => {
          const index = this.categories.findIndex(item => item.id === category.id);

          if (index > -1 && res.data) {
            this.categories.splice(index, 1, res.data);
          }

          this.ee_showAlert('defaultSuccess');
        },
        this.ee_errorHandler,
        () => this.isBusy = false
      );
    },
    createCategory (category) {
      this.isBusy = true;

      this.$subscribeTo(
        CategoryService.createCategory(category),
        res => {
          this.categories.push(res.data);
          this.ee_showAlert('defaultSuccess');
        },
        this.ee_errorHandler,
        () => this.isBusy = false
      );
    },
    deleteCategory (id) {
      this.isBusy = true;

      this.$subscribeTo(
        CategoryService.deleteCategory(id),
        () => {
          this.categories = this.categories.filter(item => item.id !== id);
          this.ee_showAlert('categoryDeleted');
        },
        this.ee_errorHandler,
        () => this.isBusy = false
      )
    },
    isDebet () {
      return !this.activeSide || this.activeSide === 'debet';
    },
    isCredit () {
      return !this.activeSide || this.activeSide === 'credit';
    },
  },
}
</script>
