export default {
  items: [
    {
      name: 'Dashboard',
      url: '/mijn-ee/dashboard',
      icon: 'icon-home',
    },
    {
      name: 'Transacties',
      url: '/mijn-ee/transacties',
      icon: 'icon-docs',
      children: [
        {
          name: 'Overzicht',
          url: '/mijn-ee/transacties/overzicht',
          icon: 'icon-list',
        },
        {
          name: 'Toevoegen',
          url: '/mijn-ee/transacties/toevoegen',
          icon: 'icon-plus',
        },
      ]
    },
    {
      name: 'Rekeningen',
      url: '/mijn-ee/rekeningen',
      icon: 'icon-star',
    },
  ]
}
