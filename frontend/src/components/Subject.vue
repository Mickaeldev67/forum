<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute } from 'vue-router';
    import api from '../api';

    const Threads = ref([]);
    const error = ref('');

    onMounted(async () => {
        try {
            const response = await api.get('/threads', {
                withCredentials: true, // Permet d'envoyer les cookies avec la requête
                headers: {
                    'Content-Type': 'application/ld+json', // Définit le type de contenu que l'on attend de l'API
                }
            });
            // Traitement de la réponse ici
        } catch (err) {
            error.value = err; // Enregistre l'erreur si la requête échoue
        }
    });
</script>

<template>
    <div class="flex justify-between">
        <h1>Liste des sujets</h1>
        <router-link to="/thread_create">Créer un sujet</router-link>
    </div>
    
    <span class="text-red-400">{{ error }}</span>
    <li v-for="thread in threads">
        <div>
            <span>{{ thread.title }}</span>
        </div>
    </li>
</template>