<template>
  <q-card flat>
    <div class="q-pa-lg">
      <div class="col col-12">
        <q-btn-toggle
          v-model="modelValue.actif"
          :toggle-color="getStaetColor"
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
          <q-btn label="Valider" type="submit" color="primary" />
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

import { evtStore } from 'src/stores/evenement';
const evtModule = evtStore();

const modelValue = ref<EvenementType>(evtModule.getCurrentEvt);
const progEvt = modelValue.value.programmation;
const listCategories = ref<categoriesType>(['randonnée', 'théatre', 'sortie']);
const actif = ref('');

const getStaetColor = computed(() => {
  return actif.value ? 'green' : 'red';
});
</script>
<style>
.slider_participants {
  padding: none;
}
</style>
