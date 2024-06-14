<template>
  <q-card flat>
    <div class="q-pa-lg">
      <div v-if="isPro" class="col col-12">
        <q-btn-toggle
          v-model="modelValue.actif"
          :toggle-color="getEtatEvenement"
          :options="[
            { label: 'En ligne', value: true, slot: 'actif' },
            { label: 'Hors ligne', value: false, slot: 'inactif' },
          ]"
        >
          <template v-slot:actif>
            <q-tooltip>Mise en ligne</q-tooltip>
          </template>

          <template v-slot:inactif>
            <q-tooltip>Mise hors ligne</q-tooltip>
          </template>
        </q-btn-toggle>
      </div>
      <div class="q-py-md">
        <gestionImage
          v-if="isPro"
          modelValue:modelValue.image
          :path="K_cheminImage"
        ></gestionImage>
      </div>
      <div class="q-py-md">
        <programmation :progEvt="progEvt" />
      </div>
      <q-form class="q-gutter-md">
        <div class="q-pt-md row">
          <div class="col-6 q-pr-md">
            <q-input
              v-model="modelValue.titre"
              label="Nom évènement*"
              counter
              maxlength="50"
              hint="titre de l'activité"
              lazy-rules
              :rules="[(val) => (val && val.length > 0) || 'champ oblogatoire']"
            />
          </div>
          <div class="col-12 q-pt-md">
            <q-input v-model="modelValue.url" label="Lien vers site évènement">
              <template v-slot:prepend>
                <q-icon name="travel_explore" />
              </template>
            </q-input>
          </div>
          <div class="col-12 q-pt-md">
            <q-select
              v-model="modelValue.categories"
              multiple
              :options="listCategories"
              use-chips
              stack-label
              label="Categories"
            />
          </div>
        </div>
        <!-- saisi adresse de l'évènement -->
        <adrManagement v-model="modelValue.adresse" />

        <div class="text-subtitle1">Description</div>
        <q-editor
          v-model="modelValue.description"
          style="min-width: 1000px"
          min-height="7rem"
        />

        <div>
          <q-btn label="Valider" @click="saveEvenement" color="primary" />
          <q-btn
            label="Annuler"
            type="reset"
            color="primary"
            flat
            class="q-ml-sm"
          />
        </div>
      </q-form>
    </div>
  </q-card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { type EvenementType, type categoriesType } from 'src/types/evenements';
import adrManagement from 'src/components/ihm/AdrManagementgouv.vue';
import programmation from 'src/components/evenement/ProgrammationEvt.vue';
import gestionImage from 'src/components/ihm/ManageImage.vue';
import { evtStore } from 'src/stores/evenement';
const evtModule = evtStore();

import { userStore } from 'src/stores/users';
const userModule = userStore();
const K_cheminImage = '/images/evenements/';

const modelValue = ref(evtModule.getCurrentEvt);
const progEvt = modelValue.value.recurrence;
const listCategories = ref<categoriesType>(['randonnée', 'théatre', 'sortie']);
const isPro = ref(userModule.getIsPro);

const getEtatEvenement = computed(() => {
  return modelValue.value.actif ? 'green' : 'red';
});

//
// sauvegarde de l'évènement
function saveEvenement() {
  console.log('prog', progRef.value.getNouvelleProg());
}
</script>
<style>
.slider_participants {
  padding: none;
}
</style>
