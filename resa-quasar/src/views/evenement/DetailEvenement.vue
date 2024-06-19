<template>
  <div class="q-pa-md">
    <q-toolbar>
      <q-btn icon="arrow_back_ios" flat @click="$router.go(-1)" />

      <q-tabs
        v-model="tab"
        dense
        inline-label
        no-caps
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
      >
        <q-tab
          name="detail"
          v-if="proprietaire"
          icon="edit_note"
          label="Modifier évènement"
        />
        <q-tab
          v-else
          name="detail"
          icon="description"
          label="Description"
        ></q-tab>
        <q-tab name="sortie" icon="groups" label="Sorties disponibles" />
      </q-tabs>
    </q-toolbar>
    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel v-if="proprietaire" name="detail"><formEvt /> </q-tab-panel>
      <q-tab-panel v-else name="detail"> <infoEvt /></q-tab-panel>

      <q-tab-panel name="sortie"> </q-tab-panel>
    </q-tab-panels>
  </div>
</template>
<script setup lang="ts">
import { ref, onBeforeMount, computed } from 'vue';
import { evenementVide } from 'src/types/evenements';

import { useRouter } from 'vue-router';
const router = useRouter();

import { userStore } from 'src/stores/users';
const userModule = userStore();

import { getCurrentEvt } from 'src/utils/cookie';
import infoEvt from 'src/components/evenement/infoEvenement.vue';
import formEvt from 'src/components/evenement/FormEvenement.vue';
const currentEvt = ref();
const tab = ref('detail');

// on regarde sur le user connecté est celui qui a créé l'évènement
const proprietaire = computed(() => {
  // récupération de l'id du suer connecté
  const currentUserId = userModule.getUserId;
  if (currentEvt.value && currentEvt.value.auteur) {
    //
    return currentUserId === currentEvt.value.auteur.id;
  } else {
    return false;
  }
});

onBeforeMount(() => {
  currentEvt.value = getCurrentEvt();
  if (typeof currentEvt.value == 'undefined') {
    currentEvt.value = evenementVide;
    router.push({ name: 'accueil' });
  }
});
</script>
<style></style>
