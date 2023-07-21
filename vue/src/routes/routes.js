import DashboardLayout from '../components/Dashboard/Layout/DashboardLayout.vue'
// GeneralViews
import NotFound from '../components/GeneralViews/NotFoundPage.vue'
// Dashboard pages
const Overview = () => import(/* webpackChunkName: "widgets" */ '../components/Dashboard/Views/Dashboard/Overview.vue')
const Widgets = () => import(/* webpackChunkName: "widgets" */ '../components/Dashboard/Views/Dashboard/Widgets.vue')

import VueRouter from 'vue-router'
import Vue from 'vue'

//Promo Profile
import PromoProfile from '../components/Dashboard/Views/PullOut/PromoProfile.vue'

// Pages
import User from '../components/Dashboard/Views/Pages/UserProfile.vue'
import TimeLine from '../components/Dashboard/Views/Pages/TimeLinePage.vue'
import Login from '../components/Dashboard/Views/Pages/Login.vue'
import Register from '../components/Dashboard/Views/Pages/Register.vue'
import OTPForm from '../components/Dashboard/Views/Pages/OTPRegister/OTPRegister.vue'
import ForgotPassword from '../components/Dashboard/Views/Pages/ForgotPassword/ForgotPassword.vue'
import LoginOTPForm from '../components/Dashboard/Views/Pages/ForgotPassword/OTPLogin.vue'
import ResetPasswordForm from '../components/Dashboard/Views/Pages/ForgotPassword/ResetPasswordForm.vue'
import Lock from '../components/Dashboard/Views/Pages/Lock.vue'

// Components pages
import Buttons from '../components/Dashboard/Views/Components/Buttons.vue'
import GridSystem from '../components/Dashboard/Views/Components/GridSystem.vue'
import Panels from '../components/Dashboard/Views/Components/Panels.vue'
import SweetAlert from '../components/Dashboard/Views/Components/SweetAlert.vue'
import Notifications from '../components/Dashboard/Views/Components/Notifications.vue'
import Icons from '../components/Dashboard/Views/Components/Icons.vue'
import Typography from '../components/Dashboard/Views/Components/Typography.vue'

// Forms pages
const RegularForms  = () => import(/* webpackChunkName: "forms" */ '../components/Dashboard/Views/Forms/RegularForms.vue')
const ExtendedForms = () => import(/* webpackChunkName: "forms" */ '../components/Dashboard/Views/Forms/ExtendedForms.vue');
const ValidationForms = () => import(/* webpackChunkName: "forms" */ '../components/Dashboard/Views/Forms/ValidationForms.vue')
const Wizard = () => import(/* webpackChunkName: "forms" */ '../components/Dashboard/Views/Forms/Wizard.vue');

// TableList pages
const RegularTables = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/Tables/RegularTables.vue');
const ExtendedTables = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/Tables/ExtendedTables.vue');
const PaginatedTables = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/Tables/PaginatedTables.vue');

// Pull - Out pages
const PullOutForm = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/PullOutGenerator.vue');
const PullOutFormCopy = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/PullOutGeneratorcopy.vue');
const PullOutFormDraft = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/DraftPullOut/DraftPullOut.vue');
const PullOutRequests = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/PullOutRequests.vue');
const Maintenance = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/Maintenance.vue');
const PullOutTransaction = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/PullOutTransaction.vue');
const AllPullOutTransaction = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/AdminPullOutTransaction.vue');
const ApprovalPullOutTransaction = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/ApprovalTransaction.vue');
const PromoAccount = () => import(/* webpackChunkName: "tables" */ '../components/Dashboard/Views/PullOut/PromoAccount.vue');
// Maps pages
const GoogleMaps = () => import(/* webpackChunkName: "maps" */ '../components/Dashboard/Views/Maps/GoogleMaps.vue')
const FullScreenMap = () => import(/* webpackChunkName: "maps" */ '../components/Dashboard/Views/Maps/FullScreenMap.vue')
const VectorMaps = () => import(/* webpackChunkName: "maps" */ '../components/Dashboard/Views/Maps/VectorMapsPage.vue');

// Calendar
import Calendar from '../components/Dashboard/Views/Calendar/CalendarRoute.vue'
// Charts
const Charts = () => import(/* webpackChunkName: "widgets" */ '../components/Dashboard/Views/Charts.vue')

let userPromoMenu = {
  path: '/user-promo',
  component: DashboardLayout,
  redirect: '/user-promo/profile',
  children: [
    {
      path: 'profile',
      name: 'My Profile',
      component: PromoProfile,
      meta: {requiresAuth: true}
    }
  ]
}

let componentsMenu = {
  path: '/components',
  component: DashboardLayout,
  redirect: '/components/buttons',
  children: [
    {
      path: 'buttons',
      name: 'Buttons',
      component: Buttons,
        meta: {requiresAuth: true},
    },
    {
      path: 'grid-system',
      name: 'Grid System',
      component: GridSystem,
        meta: {requiresAuth: true},
    },
    {
      path: 'panels',
      name: 'Panels',
      component: Panels,
        meta: {requiresAuth: true},
    },
    {
      path: 'sweet-alert',
      name: 'Sweet Alert',
      component: SweetAlert,
        meta: {requiresAuth: true},
    },
    {
      path: 'notifications',
      name: 'Notifications',
      component: Notifications,
        meta: {requiresAuth: true},
    },
    {
      path: 'icons',
      name: 'Icons',
      component: Icons,
        meta: {requiresAuth: true},
    },
    {
      path: 'typography',
      name: 'Typography',
      component: Typography,
        meta: {requiresAuth: true},
    }

  ]
}
let formsMenu = {
  path: '/forms',
  component: DashboardLayout,
  redirect: '/forms/regular',
  children: [
    {
      path: 'regular',
      name: 'Regular Forms',
      component: RegularForms,
      meta: {requiresAuth: true},
    },
    {
      path: 'extended',
      name: 'Extended Forms',
      component: ExtendedForms,
      meta: {requiresAuth: true},
    },
    {
      path: 'validation',
      name: 'Validation Forms',
      component: ValidationForms,
      meta: {requiresAuth: true},
    },
    {
      path: 'wizard',
      name: 'Wizard',
      component: Wizard,
      meta: {requiresAuth: true},
    }
  ]
}

let tablesMenu = {
  path: '/table-list',
  component: DashboardLayout,
  redirect: '/table-list/regular',
  children: [
    {
      path: 'regular',
      name: 'Regular Tables',
      component: RegularTables,
      meta: {requiresAuth: true},
    },
    {
      path: 'extended',
      name: 'Extended Tables',
      component: ExtendedTables,
      meta: {requiresAuth: true},
    },
    {
      path: 'paginated',
      name: 'Paginated Tables',
      component: PaginatedTables,
      meta: {requiresAuth: true},
    }]
}

let pullOutMenu = {
  path: '/pull-out',
  component: DashboardLayout,
  redirect: '/pull-out/requisition-form',
  children: [
    {
      path: 'requisition-form',
      name: 'Pull-Out Requisition Form',
      component: PullOutForm,
      meta: {requiresAuth: true},
    },
    {
      path: 'requisition-form-copy',
      name: 'Pull-Out Requisition Form',
      component: PullOutFormCopy,
      meta: {requiresAuth: true},
    },
    {
      path: 'requests',
      name: 'Pull-Out Requests',
      component: PullOutRequests,
      meta: {requiresAuth: true},
    },
    {
      path: 'requisition-draft',
      name: 'Pull-Out Draft',
      component: PullOutFormDraft,
      meta: {requiresAuth: true},
    },
    {
      path: 'pullout-transaction',
      name: 'Pull-Out Transaction',
      component: PullOutTransaction,
      meta: {requiresAuth: true},
    },
    {
      path: 'all-requests',
      name: 'All Pull-Out Requests',
      component: AllPullOutTransaction,
      meta: {requiresAuth: true},
    },
    {
      path: 'approval-transaction',
      name: 'For Approval Transaction',
      component: ApprovalPullOutTransaction,
      meta: {requiresAuth: true},
    },
    {
      path: 'promodisers-account',
      name: 'Promodiser\'s Account',
      component: PromoAccount,
      meta: {requiresAuth: true},
    },
    {
      path: 'maintenance',
      name: 'Maintenance',
      component: Maintenance,
      meta: {requiresAuth: true},
    }]
}

let mapsMenu = {
  path: '/maps',
  component: DashboardLayout,
  redirect: '/maps/google',
  children: [
    {
      path: 'google',
      name: 'Google Maps',
      component: GoogleMaps,
      meta: {requiresAuth: true},
    },
    {
      path: 'full-screen',
      name: 'Full Screen Map',
      component: FullScreenMap,
      meta: {requiresAuth: true},
    },
    {
      path: 'vector-map',
      name: 'Vector Map',
      component: VectorMaps,
      meta: {requiresAuth: true},
    }
  ]
}

let pagesMenu = {
  path: '/pages',
  component: DashboardLayout,
  redirect: '/pages/user',
  children: [
    {
      path: 'user',
      name: 'User Page',
      component: User,
      meta: {requiresAuth: true},
    },
    {
      path: 'timeline',
      name: 'Timeline Page',
      component: TimeLine,
      meta: {requiresAuth: true},
    }
  ]
}

let lockPage = {
  path: '/lock',
  name: 'Lock',
  component: Lock
}

const routes = [
  userPromoMenu,

  {
    path: '/',
    component: DashboardLayout,
    redirect: '/admin/overview',
    children: [
      {
        path: 'calendar',
        name: 'Calendar',
        component: Calendar,
        meta: {requiresAuth: true},
      },
      {
        path: 'charts',
        name: 'Charts',
        component: Charts,
        meta: {requiresAuth: true},
      }
    ]
  },
  {
    path: '/user-profile',
    name: 'UserProfile',
    component: User,
    meta: {requiresAuth: true}
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { isGuest: true}
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { isGuest: true}
  },
  {
    path: '/register/otp_form',
    name: 'OTPForm',
    component: OTPForm,
    meta: { isGuest: true}
  },
  {
    path: '/login/forgot_password',
    name: 'ForgotPassword',
    component: ForgotPassword,
    meta: { isGuest: true}
  },
  {
    path: '/login/otp_form',
    name: 'LoginOTPForm',
    component: LoginOTPForm,
    meta: { isGuest: true}
  },
  {
    path: '/login/reset_password',
    name: 'ResetPassword',
    component: ResetPasswordForm,
    meta: { isGuest: true}
  },
  componentsMenu,
  formsMenu,
  tablesMenu,
  mapsMenu,
  pagesMenu,
  pullOutMenu,
  lockPage,
  {
    path: '/admin',
    component: DashboardLayout,
    redirect: '/admin/overview',
    children: [
      {
        path: 'overview',
        name: 'Overview',
        component: Overview,
        meta: {requiresAuth: true},
      },
      {
        path: 'widgets',
        name: 'Widgets',
        component: Widgets,
        meta: {requiresAuth: true},
      }
    ]
  },
  {path: '*', component: NotFound}
];

Vue.use(VueRouter)

// configure router
const router = new VueRouter({
  // mode: "history",
  routes, // short for routes: routes
  linkActiveClass: 'active',
  scrollBehavior: (to) => {
    if (to.hash) {
      return {selector: to.hash}
    } else {
      return { x: 0, y: 0 }
    }
  }
})

  function getToken(){
    return sessionStorage.getItem('Token');console.log("TOKEN:", token);
  }

  router.beforeEach((to, from, next) => {
      if(to.matched.some(record => record.meta.requiresAuth)){
          if (!getToken()) {
              return next({path: '/login'});
          }
          else{
              return next();
          }
      }
      else if (to.matched.some(record => record.meta.isGuest)) {

          if (getToken()) {

            var user = sessionStorage.getItem("Position");
            console.log("Position", user);
            if(user == "Admin"){
              return next({path: '/admin/overview'});
            }else if(user == "Agent" || user == "Approver"){
              return next({path: '/pull-out/requests'});
            }else if(user == "User"){
              return next({path: '/pull-out/requisition-form'});
            }
          }
          else{
              return next();
          }
      }else {
        next();
      }
  // let userId = sessionStorage.getItem('UserID');
  });

export default router
