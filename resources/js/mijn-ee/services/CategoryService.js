import { from } from 'rxjs'
import { map } from 'rxjs/operators'

export function getCategories() {
  return from(axios.get('categories'))
    .pipe(
      map(response => response.data)
    );
}

export function orderCategories(data) {
  data = data.map((item, index) => ({ id: item.id, priority: (data.length - index) * 10 }));
  return from(axios.patch('categories/order', data));
}

export function createCategory(data) {
  return from(axios.post('categories', data));
}

export function updateCategory(id, data) {
  return from(axios.put(`categories/${id}`, data));
}

export function deleteCategory(id) {
  return from(axios.delete(`categories/${id}`));
}
