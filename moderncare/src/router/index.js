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
        // Prevent access if logged in
        beforeEnter: (to, from, next) => {
          if (localStorage.getItem('userData')) {
            next('/tabs/tabLijsten'); // Redirect to tabLijsten if already logged in
          } else {
            next(); // Allow access to login page if not logged in
          }
        }
      },
      {
        path: 'tabLijsten',
        component: () => import('@/views/LijstenView.vue'),
        meta: { requiresAuth: true }
      },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});
// Global navigation guard for routes that require authentication
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!localStorage.getItem('userData')) {
      next('/tabs/tabLogin');  // Redirect to login if not authenticated
    } else {
      next();  // Proceed if authenticated
    }
  } else {
    next();  // Proceed for routes that do not require authentication
  }
});
export default router;