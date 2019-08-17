import { from, of } from 'rxjs'
import * as helpers from './helpers'

export function getTransactions(page, filters) {
  if (helpers.noResultsExpected(filters)) {
    return of({});
  }

  const queryString = helpers.toQueryString({page, ...filters});

  return from(axios.get('transactions' + queryString));
}
