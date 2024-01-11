import { createRouter, createWebHistory } from '@ionic/vue-router';
import TabsPage from '../views/HomePage.vue';
import LijstenView from '../views/LijstenView.vue';

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
        component: () => import('@/views/LogIn.vue')
      },
      {
        path: 'tabLijsten',
        component: () => import('@/views/LijstenView.vue')
      },
      // Choose one of the following based on your preference
      // Either '/tabLijsten'
      // {
      //   path: 'tabLijsten',
      //   component: () => import('@/views/LijstenView.vue')
      // },
      // Or '/LijstenView'
      // {
      //   path: 'LijstenView',
      //   component: () => import('@/views/LijstenView.vue')
      // },
    ]
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

export default router;