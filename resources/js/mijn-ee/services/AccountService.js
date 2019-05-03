import { from } from 'rxjs'
import { map } from 'rxjs/operators'

export function getAccounts() {
  return from(axios.get('accounts'))
    .pipe(
      map(response => response.data)
    );
}

export function createAccount(data) {
  return from(axios.post('accounts', data));
}

export function updateAccount(id, data) {
  return from(axios.put(`accounts/${id}`, data));
}

export function deleteAccount(id) {
  return from(axios.delete(`accounts/${id}`));
}
