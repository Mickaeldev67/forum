import { createRouter, createWebHistory } from 'vue-router';
import Connexion from '../components/Connexion.vue';
import Subscribe from '../components/Subscribe.vue';
import Subject from '../components/Subject.vue';
import ThreadCreate from '../components/ThreadCreate.vue';
import ThreadShow from '../components/ThreadShow.vue';

const routes = [
  { path: '/', name: 'Home', component: Connexion },
  { path: '/subscribe', name: 'subscribe', component: Subscribe },
  { path: '/subject', name: 'subject', component: Subject },
  { path: '/thread_create', name: 'thread_create', component: ThreadCreate },
  { path: '/threads/:id', name: 'thread_show', component: ThreadShow },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;