export default {
  items: [
    {
      name: 'Home',
      url: '/mijn-ee/home',
      icon: 'icon-home',
    },
    {
      name: 'Dashboard',
      url: '/mijn-ee/dashboard',
      icon: 'icon-pie-chart',
      description: 'Uw financiën, overzichtelijk verwerkt in hapklare grafiekjes en tabelletjes.',
    },
    {
      name: 'Transacties',
      url: '/mijn-ee/transacties',
      icon: 'icon-docs',
      description: 'Upload transacties en bekijk het overzicht van uw transacties.',
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
      description: 'Maak categorieën aan om uw transacties gemakkelijk terug te vinden.',
    },
    {
      name: 'Rekeningen',
      url: '/mijn-ee/rekeningen',
      icon: 'icon-star',
      description: 'Bekijk uw rekeningen.',
    },
  ]
}
