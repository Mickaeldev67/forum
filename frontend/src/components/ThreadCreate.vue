<script setup>
    import { ref } from 'vue';
    import api from '../api';
    import { useRouter } from 'vue-router';

    const router = useRouter();

    const newThread = ref({ title: '', description: '' });
    const error = ref('');

    const addThread = async () => {
        try {
            const data = {
                "@context": "/api/contexts/Thread",
                "@type": "Thread",
                "title": newThread.value.title,
                "description": newThread.value.description
            }

            const response = await api.post('/threads', data, {
                withCredentials: true,
                headers: {
                    'Content-Type': 'application/ld+json',
                }
            });

            console.log(response.data);
            router.push('/subject');
            
        } catch (err) {
            error.value = err;
        }
        
    }
</script>

<template>
    
    <form @submit.prevent="addThread" class="flex justify-center">
        <fieldset class="w-full flex flex-col items-center gap-4">
            <div class="flex justify-between w-screen p-4">
                <h1 class="text-xl">Créer un sujet</h1>
                <router-link to="/subject" class="border px-2 rounded hover:bg-gray-200">Retour aux sujets</router-link>
            </div>
            
            <input v-model="newThread.title" type="text" placeholder="Titre" class="w-1/2 rounded border py-1 px-2">
            <input type="text" v-model="newThread.description" placeholder="description" class="w-1/2 rounded border py-1 px-2">
            <span v-if="error" class="text-red-400">{{ error }}</span>
            <button type="submit" class="border rounded px-2 py-1 hover:bg-gray-200">Créer un sujet</button>
        </fieldset>
    </form>
    
</template>