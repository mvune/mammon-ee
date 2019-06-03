import { from, of } from 'rxjs'
import * as helpers from './helpers'

export function getBoundaryDates() {
  return from(axios.get('charts/boundary-dates'));
}

export function getSheetData(accounts, categories) {
  for (let param of [accounts, categories]) {
    if (_.isArray(param) && _.isEmpty(param)) {
      return of({});
    }
  }
  
  const queryString = helpers.toQueryString({accounts, categories});

  return from(axios.get('charts/sheet-data' + queryString));
}
