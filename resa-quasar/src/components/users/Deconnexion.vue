<template>
  <v-tooltip bottom>
    <template v-slot:activator="{ on }">
      <v-btn color="red" dark icon v-on="on" @click="deconnexion()"><v-icon>mdi-account-remove-outline</v-icon></v-btn>
    </template>
    <span>déconnexion</span>
  </v-tooltip>
</template>

<script setup lang="ts">
import { userStore } from 'src/stores/users';
import { useRouter } from 'vue-router'

const connexionModule = userStore()
const router = useRouter()

async function deconnexion() {
  await connexionModule.deconnexion();
  // onaffiche la page d'accueil
  router.push({ name: 'connexion' }).catch(() => { });
}
</script>

<style></style>
