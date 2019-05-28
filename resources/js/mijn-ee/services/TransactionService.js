import { from, of } from 'rxjs'

export function getTransactions(page, accounts, categories) {
  for (let param of [accounts, categories]) {
    if (_.isArray(param) && _.isEmpty(param)) {
      return of({});
    }
  }

  const params = new URLSearchParams;

  if (page) params.append('page', page);
  if (accounts) params.append('accounts', accounts);
  if (categories) params.append('categories', categories);

  const queryString = params.toString() ? '?' + params.toString() : '';

  return from(axios.get('transactions' + queryString));
}
