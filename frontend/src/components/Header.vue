<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
  <header class="bg-gray-800 text-gray-200 px-4 py-2 flex justify-between">
    <router-link to="/">Forum 2025</router-link>
    
    <!-- Vérifie la route active et affiche le lien approprié -->
    <router-link v-if="route.path === '/subscribe'" to="/login">Connexion</router-link>
    <router-link v-if="route.path === '/'" to="/subscribe">S'inscrire</router-link>
    <router-link v-else to="/">Déconnexion</router-link>
  </header>
</template>