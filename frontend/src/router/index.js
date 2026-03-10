import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import FeedView from '../views/FeedView.vue'
import AdminView from '../views/AdminView.vue'

const routes = [
  {
    path: '/',
    redirect: '/feed',
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: { guest: true },
  },
  {
    path: '/feed',
    name: 'feed',
    component: FeedView,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin',
    name: 'admin',
    component: AdminView,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || 'null')

  if (to.meta.requiresAuth && !token) {
    next({ name: 'login' })
  } else if (to.meta.guest && token) {
    next({ name: 'feed' })
  } else if (to.meta.requiresAdmin && (!user || user.role !== 'admin')) {
    next({ name: 'feed' })
  } else {
    next()
  }
})

export default router
