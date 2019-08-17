import { from, of } from 'rxjs'
import * as helpers from './helpers'

const getBoundaryDatesObs = (function () {
  const obs = from(axios.get('charts/boundary-dates'));

  return function () { return obs; }
})();

export function getBoundaryDates() {
  return getBoundaryDatesObs();
}

export function getSheetData([accounts, categories, dateFrom, dateTo]) {
  for (let param of [accounts, categories]) {
    if (_.isArray(param) && _.isEmpty(param)) {
      return of({});
    }
  }
  
  const queryString = helpers.toQueryString({accounts, categories, dateFrom, dateTo});

  return from(axios.get('charts/sheet-data' + queryString));
}
