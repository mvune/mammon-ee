import { from, of } from 'rxjs'

export function getTransactions(page, accounts) {
  if (_.isArray(accounts) && _.isEmpty(accounts)) {
    return of({});
  }

  const params = new URLSearchParams;

  if (page) params.append('page', page);
  if (accounts) params.append('accounts', accounts);

  const queryString = params.toString() ? '?' + params.toString() : '';

  return from(axios.get('transactions' + queryString));
}
