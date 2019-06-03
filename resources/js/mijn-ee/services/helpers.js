export function toQueryString({page, accounts, categories}) {
  const params = new URLSearchParams;

  if (page) params.append('page', page);
  if (accounts) params.append('accounts', accounts);
  if (categories) params.append('categories', categories);

  return params.toString() ? '?' + params.toString() : '';
}
