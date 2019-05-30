import { from } from 'rxjs'

export function getFiltersData() {
  return from(axios.get('charts/filters-data'));
}

export function getSheetData() {
  return from(axios.get('charts/sheet-data'));
}
