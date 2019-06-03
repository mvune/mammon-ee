import { from, of } from 'rxjs'
import * as helpers from './helpers'

export function getTransactions(page, accounts, categories) {
  for (let param of [accounts, categories]) {
    if (_.isArray(param) && _.isEmpty(param)) {
      return of({});
    }
  }

  const queryString = helpers.toQueryString({page, accounts, categories});

  return from(axios.get('transactions' + queryString));
}
