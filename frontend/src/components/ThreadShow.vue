<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/api';
const route = useRoute();
const posts = ref<Post[]>([]);
const thread = ref<Thread>({id:-1, title:'', description: '', author: { username:'' }, formattedCreatedAt:''});
const error = ref<string>('');
const newPost = ref('');
const idThread = ref<number>(-1);

interface Thread {
    id: number;
    title: string;
    description: string;
    author: {
        username: string;
    };
    formattedCreatedAt: string;
}

interface Post {
    id: number;
    content: string;
    author: {
        username: string;
    };
    formattedCreatedAt: string;
}

onMounted(async () => {
    const threadId = route.params.id;
    idThread.value = Number(threadId);

    try {
        const responseThread = await api.get(`/threads/${idThread.value}`, {
                withCredentials: true, // Permet d'envoyer les cookies avec la requête
                headers: {
                    'Content-Type': 'application/ld+json', // Définit le type de contenu que l'on attend de l'API
                }
            });
        thread.value = responseThread.data;
        fetchPosts();
        setInterval(fetchPosts, 5000)
    } catch (err) {
        console.error('Erreur Thread', err);
        error.value = String(err);
    } 
});

const fetchPosts = async () => {
    try {
        const response = await api.get('/posts', {
            params: {
                thread: `/api/threads/${idThread.value}`
            },
            withCredentials: true,
            headers: {
                'Accept': 'application/ld+json',
            },
        });

        posts.value = response.data.member;
    } catch (err) {
        error.value = String(err);
    }
}

const addPost = async () => {
    try {
        const threadIri = `/api/threads/${idThread.value}`;
        const data = {
            "@context": "/api/contexts/Post",
            "@type": "Post",
            "content": newPost.value,
            "thread": threadIri
        };

        console.log("idthread: ", idThread.value);

        const response = await api.post('/posts', data, {
            withCredentials: true,
            headers: {
                'Content-Type': 'application/ld+json',
            },
        });
        newPost.value = '';
        fetchPosts();
    } catch (err) {
        error.value = String(err);
    }
}
</script>

<template>
    <span v-if="error">{{ error }}</span>
    <div v-if="thread" class="p-4">
        <div class="flex justify-between">
            <h1 class="text-xl">{{ thread?.title }}</h1>
            <router-link to="/subject" class="border rounded hover:bg-gray-200 px-2">Retour aux sujets</router-link>
        </div>
        
        <div>
            <h2>Créé par : {{ thread.author?.username }}</h2>
            <h3>Le {{ thread?.formattedCreatedAt }}</h3>
        </div>
        
        <li v-for="post in posts" :key="post.id" class="list-none">
            <div v-if="post" class="bg-gray-200 my-2 p-2 rounded">
                <span>Par <strong>{{ post.author.username }}</strong></span>
                
                <p>{{ post.content }}</p>
                <span>Le <i>{{ post.formattedCreatedAt }}</i></span>
            </div>
        </li>

        <form @submit.prevent="addPost" class="w-full">
            <textarea v-model="newPost" class="p-2 w-full border rounded" placeholder="Veuillez écrire un post..."></textarea>
            <button class="border rounded px-2 hover:bg-gray-200" type="submit">Envoyer mon post</button>
        </form>
    </div>
    
</template>