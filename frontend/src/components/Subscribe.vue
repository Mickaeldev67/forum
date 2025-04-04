<script setup>
    import Header from './Header.vue';
    import { ref } from 'vue';

    const input = ref({ email: '', username: '', password:'', passwordverif:'' });
    const error = ref('');
    const subscribe = async () => {
        // Vérification de mot de passe 
        if (input.value.password !== input.value.passwordverif) {
            error.value = "Mots de passe différents";
            return;
        }
        // Logique de création de compte

    }
</script>

<template>
    <form @submit.prevent="subscribe">
        <div class="flex flex-col items-center w-full gap-2">
            <h1 class="text-2xl">Inscription</h1>
            <input v-model="input.email" class="w-1/2 py-1 px-2" type="email" placeholder="adresse@email.com" required> 
            <input v-model="input.username" class="w-1/2 py-1 px-2" type="text" placeholder="Pseudonyme" required>
            <input v-model="input.password" class="w-1/2 py-1 px-2" type="password" placeholder="Mot de passe" required>
            <input v-model="input.passwordverif" class="w-1/2 py-1 px-2" type="password" placeholder="Confirmez votre mot de passe" required>
            <button class="hover:bg-gray-200 rounded-xl border px-2" type="submit">S'inscrire</button>
            <span v-if="error" class="text-red-500">{{ error }}</span>
        </div>
        
    </form>
</template>