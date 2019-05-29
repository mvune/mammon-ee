import { from } from 'rxjs'

export function getSheetData() {
  return from(axios.get('charts/sheet-data'));
}
