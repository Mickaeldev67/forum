<script setup>
import { ref, onMounted} from 'vue';
import api from './api.js';
import Header from './components/Header.vue';

const posts = ref([]);
const newPost = ref({ title: '', content: '' });

onMounted(async () => {
  try {
    const response = await api.get('/posts');
    posts.value = response.data.member;
    console.log(response);
  } catch (error) {
    console.error('Erreur API:', error);
  }
});

const addPost = async () => {
  try {
    const data = {
      "@context": "/api/contexts/Post",
      "@type": "Post",
      "title": newPost.value.title,
      "content": newPost.value.content
    };

    const response = await api.post('/posts', data, {
      headers: {
        'Content-Type': 'application/ld+json',
      },
    });

    console.log("Réponse API:", response);

    // Vérifier si posts.value est un tableau avant de faire un push
    if (Array.isArray(posts.value)) {
      posts.value.push(response.data);
      newPost.value = { title: '', content: '' }; // Réinitialisation du formulaire
    } else {
      console.error('posts.value n\'est pas un tableau:', posts.value);
    }
  } catch (error) {
    console.error('Erreur ajout post:', error.response?.data || error);
  }
};
</script>

<template>
  <Header/>
  <div>
    <h1 class="text-3xl">Liste des Posts</h1>
    <ul>
      <li v-for="post in posts" :key="post.id">
        <div class="bg-gray-300 rounded-md m-2 p-2">
          <h3>{{ post.title }}</h3>
          <p>{{ post.content }}</p>
          <small>Créé le : {{  new Date(post.createdAt).toLocaleString() }}</small>
        </div>
      </li>
    </ul>
  </div>
  
  <form @submit.prevent="addPost" class="flex flex-col items-center">
    <h2 class="text-2xl">Ajouter un Post</h2>
    <input v-model="newPost.title" placeholder="Titre" required />
    <textarea v-model="newPost.content" placeholder="Contenu" required></textarea>
    <button type="submit">Publier</button>
  </form>
</template>

<style scoped>
</style>
