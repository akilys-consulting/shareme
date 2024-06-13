<template>
  <q-card flat>
    <q-card-section>
      <div class="text-h5 text-primary">Ajouter un nouvel évènement</div>
    </q-card-section>
    <ajoutEvenement></ajoutEvenement>
  </q-card>
</template>
<script setup lang="ts">
import ajoutEvenement from 'src/components/evenement/FormEvenement.vue';

import { onBeforeMount, onBeforeUpdate } from 'vue';
import { storeToRefs } from 'pinia';

import { evtStore } from 'src/stores/evenement';
const evtModule = evtStore();

import { userStore } from 'src/stores/users';
const userModule = userStore();

import { evenementVide } from 'src/types/evenements';

onBeforeMount(() => {
  //
  // on renseigne l'auteur qui est le user connecté
  const userData = userModule.getUserConnected;
  evenementVide.auteurId = userData.value.id;

  evtModule.setCurrentEvt(evenementVide);
  console.log('onBeforeMount');
});
onBeforeUpdate(() => {
  console.log('onBeforeUpdate');
});
//
// on initialise un évènement
</script>
