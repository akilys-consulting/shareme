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
      <!--
      <div class="q-py-md">
        <programmation />
      </div>
-->
      <q-form class="q-gutter-md">
        <div class="q-pt-md row">
          <div class="col-6 q-pr-md">
            <q-input
              v-model="modelValue.titre"
              label="Nom évènement*"
              counter
              maxlength="50"
              hint="Name and surname"
              lazy-rules
              :rules="[
                (val) => (val && val.length > 0) || 'Please type something',
              ]"
            />
          </div>
          <div class="col-6">
            <q-select
              v-model="modelValue.auteurid"
              :options="lstOrganisateur"
              label="Organisateur"
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
import { ref, computed, onMounted } from 'vue';
import { type EvenementType, type categoriesType } from 'src/types/evenements';
import { type UserType } from 'src/types/users';
import adrManagement from 'src/components/ihm/AdrManagementgouv.vue';

import { evtStore } from 'src/stores/evenement';
const evtModule = evtStore();

import { userStore } from 'src/stores/users';
const userModule = userStore();

const modelValue = ref<EvenementType>(evtModule.getCurrentEvt);

onMounted(() => {
  const user: UserType = userModule.getUserConnected;
  const auteur: UserType = { name: user.name, id: user.id };
});

const listCategories = ref<categoriesType>(['randonnée', 'théatre', 'sortie']);
const lstOrganisateur = ref(['paul', 'pierre', 'jacques']);
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
