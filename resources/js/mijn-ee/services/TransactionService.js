import { from, of } from 'rxjs'
import * as helpers from './helpers'

export function getTransactions(page, [accounts, categories, dateFrom, dateTo]) {
  for (let param of [accounts, categories]) {
    if (_.isArray(param) && _.isEmpty(param)) {
      return of({});
    }
  }

  const queryString = helpers.toQueryString({page, accounts, categories, dateFrom, dateTo});

  return from(axios.get('transactions' + queryString));
}
