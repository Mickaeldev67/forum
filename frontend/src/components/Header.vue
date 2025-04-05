<script setup>
import { useRoute } from 'vue-router';
import { ref, onMounted, watch } from 'vue';
import api from '../api';

const route = useRoute();
const username = ref('');

const getCurrentUser = async () => {
  const response = await api.get('currentUser', {
    withCredentials: true
  });
  username.value = response.data.username;
}

onMounted(() => {
  getCurrentUser();
});

// watch(() => route.fullPath, () => {
//   getCurrentUser();
// });

</script>

<template>
  <header class="bg-gray-800 text-gray-200 px-4 py-2 flex justify-between">
    <router-link to="/">Forum 2025</router-link>
    
    <!-- Vérifie la route active et affiche le lien approprié -->
    <router-link v-if="route.path === '/subscribe'" to="/">Connexion</router-link>
    <router-link v-if="route.path === '/'" to="/subscribe">S'inscrire</router-link>
    <div v-if="username" class="flex gap-3">
      <span >{{ username }}</span>
      <router-link to="/">Déconnexion</router-link>
    </div>
    
  </header>
</template>