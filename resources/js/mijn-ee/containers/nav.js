export default {
  items: [
    {
      name: 'Dashboard',
      url: '/mijn-ee/dashboard',
      icon: 'icon-home',
    },
    {
      name: 'Visueel',
      url: '/mijn-ee/visueel',
      icon: 'icon-eye',
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
      name: 'Categorieën',
      url: '/mijn-ee/categorieen',
      icon: 'icon-tag',
    },
    {
      name: 'Rekeningen',
      url: '/mijn-ee/rekeningen',
      icon: 'icon-star',
    },
  ]
}
