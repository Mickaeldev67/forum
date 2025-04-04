<script setup>
import { ref } from 'vue'
import Header from './Header.vue';
</script>

<template>
  <form @submit.prevent="Connexion" class="flex flex-col items-center">
    <h1 class="text-xl">Page de connexion</h1>
    <input class="rounded-xl p-2" type="email" placeholder="adresse@email.com">
    <input class="rounded-xl p-2" type="password" placeholder="Mot de passe">
    <button class="border rounded-xl px-2">Connexion</button>
  </form>
</template>
