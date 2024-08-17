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
        path: 'tabPatient',
        component: () => import('@/views/AdminPage.vue'),
        meta: { requiresAdmin: true }
      },
      {
        path: 'tabDatabase',
        component: () => import('@/views/Database.vue'),
        meta: { requiresAdmin: true }
      },
      {
        path: 'tabAbout',
        component: () => import('@/views/AboutView.vue')
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0 };
  }
});

router.beforeEach((to, from, next) => {
  const userData = JSON.parse(localStorage.getItem('userData'));
  
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!userData) {
      next('/tabs/tabLogin');
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (!userData || userData.IsAdmin != 1) {
      alert('You do not have access to this page.');
      next(false);
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
