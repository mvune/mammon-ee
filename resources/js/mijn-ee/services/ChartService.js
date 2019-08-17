import { from, of } from 'rxjs'
import * as helpers from './helpers'

const getBoundaryDatesObs = (function () {
  const obs = from(axios.get('charts/boundary-dates'));

  return function () { return obs; }
})();

export function getBoundaryDates() {
  return getBoundaryDatesObs();
}

export function getDonutData(filters) {
  if (helpers.noResultsExpected(filters)) {
    return of({});
  }
  
  const queryString = helpers.toQueryString(filters);
  
  return from(axios.get('charts/doughnut-data' + queryString));
}

export function getSheetData(filters) {
  if (helpers.noResultsExpected(filters)) {
    return of({});
  }
  
  const queryString = helpers.toQueryString(filters);

  return from(axios.get('charts/sheet-data' + queryString));
}
