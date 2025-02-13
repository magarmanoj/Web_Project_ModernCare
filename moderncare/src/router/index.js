import { createRouter, createWebHistory } from '@ionic/vue-router';
import TabsPage from '../views/HomePage.vue';

const routes = [
  {
    path: '/',
    redirect: '/tabs/tabHome'
  },
  {
    path: '/tabs/',
    component: TabsPage,
    children: [
      {
        path: '',
        redirect: '/tabs/tabHome'
      },
      {
        path: 'tabHome',
        component: () => import('@/views/PatientWindow.vue')
      },
      {
        path: 'tabLogin',
        component: () => import('@/views/LogIn.vue'),
        beforeEnter: (to, from, next) => {
          if (localStorage.getItem('userData')) {
            next('/tabs/tabLijsten'); 
          } else {
            next();
          }
        }
      },
      {
        path: 'tabLijsten',
        component: () => import('@/views/LijstenView.vue'),
      },
      {
        path: 'tabAdmin',
        component: () => import('@/views/AdminPage.vue'),
        meta: { requiresAdmin: true } // Require admin role to access
      },
      {
        path: 'tabDatabase',
        component: () => import('@/views/Database.vue'),
        meta: { requiresAdmin: true }
      },
      {
        path: 'tabAbout',
        component: () => import('@/views/About.vue')
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

// Global navigation guard for authentication and authorization
router.beforeEach((to, from, next) => {
  const userData = JSON.parse(localStorage.getItem('userData'));
  
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!userData) {
      next('/tabs/tabLogin');  // Redirect to login if not authenticated
    } else {
      next();  // Proceed if authenticated
    }
  } else if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (!userData || userData.IsAdmin != 1) {
      alert('You do not have access to this page.'); // Alert for non-admin access
      next(false);  // Prevent access to the page
    } else {
      next();  // Proceed if the user is an admin
    }
  } else {
    next();  // Proceed for routes that do not require authentication or specific roles
  }
});

export default router;
