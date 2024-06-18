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
        <q-tab name="detail" icon="description" label="Description" />
        <q-tab name="sortie" icon="groups" label="Sorties disponibles" />
        <q-tab
          name="modifEvent"
          v-if="proprietaire"
          icon="edit_note"
          label="Modifier évènement"
        />
      </q-tabs>
    </q-toolbar>
    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="detail">
        <q-card>
          <q-card-section horizontal>
            <displayImgCategorie
              :titre="currentEvt.titre"
              :categorieTab="currentEvt.categories"
            />
            <q-card-section>
              <q-card-section>
                <div class="text-h4 col-12">{{ currentEvt.titre }}</div>
              </q-card-section>
              <q-separator />
              <q-card-section>
                <div class="text-subtitle1 col-12">
                  Organisateur : Mairie de Toulouse
                </div>
              </q-card-section>

              <q-separator />
              <q-card-section>
                <div class="text-subtitle1">Catégorie : balade, chateau</div>
              </q-card-section>

              <q-separator />
              <q-card-section>
                <div class="text-body1">
                  {{ currentEvt.description }}
                </div>
              </q-card-section>
            </q-card-section>
          </q-card-section>
          <div class="q-ma-sm" id="map"></div>
        </q-card>
      </q-tab-panel>

      <q-tab-panel name="sortie"> </q-tab-panel>

      <q-tab-panel name="modifEvent"> </q-tab-panel>
    </q-tab-panels>
  </div>
</template>
<script setup lang="ts">
import { ref, onBeforeMount, computed } from 'vue';

import { useRouter } from 'vue-router';
const router = useRouter();

import { evtStore } from 'src/stores/evenement';
const evtModule = evtStore();

import { userStore } from 'src/stores/users';
const userModule = userStore();

import { EvenementType } from 'src/types/evenements';
import displayImgCategorie from 'src/components/ihm/LoadImgCategorie.vue';

const currentEvt = ref<EvenementType>();

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
  currentEvt.value = evtModule.getCurrentEvt;
});

const tab = ref('detail');
</script>
<style></style>
