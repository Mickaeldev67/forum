import { createRouter, createWebHistory } from 'vue-router';
import Connexion from '../components/Connexion.vue';
import Subscribe from '../components/Subscribe.vue';

const routes = [
  { path: '/', name: 'Home', component: Connexion },
   { path: '/subscribe', name: 'Subscribe', component: Subscribe }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;