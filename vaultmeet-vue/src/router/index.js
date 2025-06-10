import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from '@/views/AboutView.vue'
import LoginView from '@/views/loginView.vue'
import NeweventView from '@/views/NeweventView.vue'
import EventListView from '@/views/EventListView.vue'
import ResetPasswordView from '@/views/ResetPasswordView.vue'
const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    component: AboutView
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/newevent',
    name: 'NeweventView',
    component: NeweventView
  },
  {
    path: '/neweventview',
    name: 'neweventview',
    component: () => import('@/views/NeweventView.vue')
  },
   {
    path: '/eventlistview',
    name: 'EventListView',
    component:  EventListView
  },
  {
    path: '/new-pass',
    name: 'ResetPassword',
    component: ResetPasswordView
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
