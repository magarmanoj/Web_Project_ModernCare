import { createRouter, createWebHistory } from '@ionic/vue-router';
import TabsPage from '../views/HomePage.vue'

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
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
