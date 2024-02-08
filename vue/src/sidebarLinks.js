export default [
  {
    name: 'UserPromo',
    access: 'All',
    children: [
      {
        name: 'My Profile',
        path: '/user-promo/profile',
        access: 'User'
      },
      {
        name: 'Logout',
        path: '/logout',
        access: 'All'
      }
    ]
  },
  {
    name: 'Dashboard',
    icon: 'nc-icon nc-bank',
    path: '/admin/overview',
    access: 'Admin'
  },
  {
    name: 'Pages',
    icon: 'nc-icon nc-book-bookmark',
    access: 'Admin',
    children: [
      {
        name: 'Timeline',
        path: '/pages/timeline',
        access: 'Admin'
      },
      {
        name: 'User Profile',
        path: '/pages/user',
        access: 'Admin'
      },
      {
        name: 'Lock',
        path: '/lock',
        access: 'Admin'
      }
    ]
  },
  {
    name: 'BBMS',
    icon: 'nc-icon nc-shop',
    access: 'Admin',
    children: [
      {
        name: 'BBMS Home Page',
        path: '/bbms/bbmshome',
        access: 'Admin'
      },
      {
        name: 'BBMS New Form',
        path: '/bbms/bbmsform',
        access: 'Admin'
      },
      {
        name: 'BBMS Branch Deactivation',
        path: '/bbms/bbmsbranchdeactivation',
        access: 'Admin'
      },
      {
        name: 'BBMS Brand Deactivation',
        path: '/bbms/bbmsbranddeactivation',
        access: 'Admin'
      },
    ]
  },
  {
    name: 'Components',
    icon: 'nc-icon nc-layout-11',
    access: 'Admin',
    children: [{
      name: 'Buttons',
      path: '/components/buttons',
      access: 'Admin'
    },
    {
      name: 'Grid System',
      path: '/components/grid-system',
      access: 'Admin'
    },
    {
      name: 'Panels',
      path: '/components/panels',
      access: 'Admin'
    },
    {
      name: 'Sweet Alert',
      path: '/components/sweet-alert',
      access: 'Admin'
    },
    {
      name: 'Notifications',
      path: '/components/notifications',
      access: 'Admin'
    },
    {
      name: 'Icons',
      path: '/components/icons',
      access: 'Admin'
    },
    {
      name: 'Typography',
      path: '/components/typography',
      access: 'Admin'
    }]
  },
  {
    name: 'Forms',
    icon: 'nc-icon nc-ruler-pencil',
    access: 'Admin',
    children: [{
      name: 'Regular Forms',
      path: '/forms/regular',
      access: 'Admin'
    },
    {
      name: 'Extended Forms',
      path: '/forms/extended',
      access: 'Admin'
    },
    {
      name: 'Validation Forms',
      path: '/forms/validation',
      access: 'Admin'
    },
    {
      name: 'Wizard',
      path: '/forms/wizard',
      access: 'Admin'
    }
    ]
  },
  {
    name: 'Table List',
    icon: 'nc-icon nc-single-copy-04',
    collapsed: true,
    access: 'Admin',
    children: [{
      name: 'Regular Tables',
      path: '/table-list/regular',
      access: 'Admin'
    },
    {
      name: 'Extended Tables',
      path: '/table-list/extended',
      access: 'Admin'
    },
    {
      name: 'Paginated Tables',
      path: '/table-list/paginated',
      access: 'Admin'
    }
    ]
  },
  {
    name: 'Pull-Out',
    icon: 'nc-icon nc-single-copy-04',
    collapsed: true,
    access: 'All',
    children: [{
      name: 'Pull-Out Form',
      path: '/pull-out/requisition-form',
      access: 'Reviewer',
      access2: 'Reviewer'

    },
    {
      name: 'Pull-Out Draft Documents',
      path: '/pull-out/requisition-draft',
      access: 'Reviewer',
      access2: 'Reviewer'
    },
    {
      name: 'Pull-Out Transactions',
      path: '/pull-out/pullout-transaction',
      access: 'User'
    },
    {
      name: 'Pull-Out Requests',
      path: '/pull-out/requests',
      access: 'Reviewer',
      access2: 'Approver'


    },
    {
      name: 'All Pull-Out Requests',
      path: '/pull-out/all-requests',
      access: 'Admin'
    },
    {
      name: 'For Approval Transaction',
      path: '/pull-out/approval-transaction',
      access: 'Admin'
    },
    {
      name: 'Promodiser\'s Account',
      path: '/pull-out/promodisers-account',
      access: 'Reviewer',
      access2: 'Approver'

    },
    {
      name: 'Maintenance',
      path: '/pull-out/maintenance',
      access: 'Admin'
    },
    ]
  },
  {
    name: 'Timekeeping System',
    icon: 'nc-icon nc-time-alarm',
    path: '/admin/timekeeping',
    access: 'Admin'
  },
  {
    name: 'Repeat Orders',
    icon: 'nc-icon nc-box-2',
    path: '/admin/repeat-orders',
    access: 'Admin'
  },
  {
    name: 'Sales Report (DSR)',
    icon: 'nc-icon nc-paper',
    path: '/admin/sales-report',
    access: 'Admin'
  },
  {
    name: 'Maps',
    icon: 'nc-icon nc-pin-3',
    access: 'Admin',
    children: [{
      name: 'Google Maps',
      path: '/maps/google',
      access: 'Admin'
    },
    {
      name: 'Full Screen Maps',
      path: '/maps/full-screen',
      access: 'Admin'
    },
    {
      name: 'Vector Maps',
      path: '/maps/vector-map',
      access: 'Admin'
    }
    ]
  },
  {
    name: 'Widgets',
    icon: 'nc-icon nc-box',
    path: '/admin/widgets',
    access: 'Admin'
  },
  {
    name: 'Charts',
    icon: 'nc-icon nc-chart-bar-32',
    path: '/charts',
    access: 'Admin'
  },
  {
    name: 'Calendar',
    icon: 'nc-icon nc-calendar-60',
    path: '/calendar',
    access: 'Admin'
  },
]
