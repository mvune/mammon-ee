export function toQueryString({page, accounts, categories, dateFrom, dateTo}) {
  const params = new URLSearchParams;

  if (page) params.append('page', page);
  if (accounts) params.append('accounts', accounts);
  if (categories) params.append('categories', categories);
  if (dateFrom) params.append('from', dateFrom);
  if (dateTo) params.append('to', dateTo);

  return params.toString() ? '?' + params.toString() : '';
}

export function noResultsExpected({accounts, categories}) {
  for (let param of [accounts, categories]) {
    if (_.isArray(param) && _.isEmpty(param)) {
      return true;
    }
  }
  
  return false;
}
