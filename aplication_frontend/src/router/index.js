import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import NoteView from '../views/NoteView.vue'
import RegisterView from '../views/RegisterView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView
  },
  {
    path: '/notes/:id',
    name: 'notes',
    component: NoteView,
    meta: { requiresAuth: true } // Indica que esta ruta requiere autenticación
  },
  {
    path: '/about',
    name: 'about',
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

// Guard de navegación
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const isAuthenticated = localStorage.getItem('token') !== null; // Verifica si hay un token en localStorage

  if (requiresAuth && !isAuthenticated) {
    next({ name: 'home' }); // Redirige a la página de inicio si no está autenticado
  } else {
    next(); // Continúa a la ruta solicitada
  }
});

export default router
