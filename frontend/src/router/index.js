import { createRouter, createWebHistory } from 'vue-router';
import Connexion from '../components/Connexion.vue';
import Subscribe from '../components/Subscribe.vue';
import Subject from '../components/Subject.vue';
import ThreadCreate from '../components/ThreadCreate.vue';

const routes = [
  { path: '/', name: 'Home', component: Connexion },
  { path: '/subscribe', name: 'Subscribe', component: Subscribe },
  { path: '/subject', name: 'Subject', component: Subject },
  { path: '/thread_create', name: 'ThreadCreate', component: ThreadCreate },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;