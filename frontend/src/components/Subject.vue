<script setup>
    import { ref, onMounted } from 'vue';
    import api from '../api';

    const threads = ref([]);
    const error = ref('');

    onMounted(async () => {
        try {
            const response = await api.get('/threads', {
                withCredentials: true, // Permet d'envoyer les cookies avec la requête
                headers: {
                    'Content-Type': 'application/ld+json', // Définit le type de contenu que l'on attend de l'API
                }
            });
            threads.value = response.data.member;
            // Traitement de la réponse ici
        } catch (err) {
            error.value = err; // Enregistre l'erreur si la requête échoue
        }
    });
</script>

<template>
    <div class="flex justify-between p-4">
        <h1 class="text-xl">Liste des sujets</h1>
        <router-link to="/thread_create" class="border px-2 rounded hover:bg-gray-200">Créer un sujet</router-link>
    </div>
    
    <span class="text-red-400">{{ error }}</span>
    <li v-for="thread in threads" :key="thread.id" class="list-none px-3 py-1">
        <router-link :to="{ name: 'thread_show', params: { id: thread.id }}" 
                    class="flex gap-4 bg-gray-300 p-3 justify-between rounded-lg hover:text-gray-100">
            <div>
                <span>{{ thread.title }}</span>
                <p>{{ thread.description }}</p>
            </div>
            
            <div>
                <p>{{ thread.author.username }}</p>
                <span>{{ thread.formattedCreatedAt }}</span>
            </div>
            
        </router-link>
    </li>
</template>