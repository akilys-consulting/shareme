<template>
  <q-header class="bg-white q-py-md text-primary text-color">
    <q-toolbar flat class="q-mr-sm">
      ShareMe<q-separator />
      <q-toolbar-title class="text-center">
        <q-tabs v-model="tab" class="text-teal">
          <q-btn
            name="home"
            flat
            icon="home"
            label="accueil"
            :to="{ name: 'accueil' }"
            class="q-pa-sm q-ma-sm item_menu"
          />
          <q-btn
            name="search"
            flat
            label="filtrer"
            icon="manage_search"
            class="q-pa-sm q-ma-sm item_menu"
            @click="icon = true"
          >
          </q-btn>

          <q-btn
            name="event"
            label="ajouter"
            flat
            icon="edit_calendar"
            class="q-pa-sm q-ma-sm C item_menu"
          />
          <q-btn
            name="profil"
            flat
            icon="record_voice_over"
            color="green"
            class="q-pa-sm q-ma-sm"
            v-if="connected"
            label="Profil"
          >
            <q-menu>
              <q-list style="min-width: 100px">
                <q-item clickable v-close-popup @click="deconnecter">
                  <q-item-section avatar>
                    <q-icon color="red" name="logout" />
                  </q-item-section>

                  <q-item-section>Déconnexion</q-item-section>
                </q-item>
                <q-item clickable v-close-popup>
                  <q-item-section avatar>
                    <q-icon color="primary" name="badge" />
                  </q-item-section>
                  <q-item-section>Profil</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
          <q-btn
            name="profil"
            flat
            label="Profil"
            v-else
            :to="{ name: 'connexion' }"
            icon="person"
            class="q-pa-sm q-ma-sm item_menu"
          />
        </q-tabs>
      </q-toolbar-title>
      {{ dateJour }} <q-separator />
    </q-toolbar>
  </q-header>
  <q-dialog v-model="icon" position="top">
    <q-card class="fond_menu">
      <q-card-section class="row items-center q-pb-none">
        <div class="text-h6">Sélectionner vos critères</div>
        <q-space />
        <q-btn icon="close" color="primary" round dense v-close-popup />
      </q-card-section>
      <q-card-section>
        <q-select
          filled
          v-model="catFilters"
          multiple
          label="Choix des catégories"
          :options="categorie"
          style="width: 450px"
          class="fond_menu"
        />
        <q-separator />
        Uniquement mes évènements
        <q-toggle
          v-model="mesEvt"
          class="item_menu"
          checked-icon="check"
          left-label
        />
      </q-card-section>
      <q-card-actions align="center"
        ><q-btn color="primary" flat>valider</q-btn></q-card-actions
      >
    </q-card></q-dialog
  >
</template>

<script lang="ts" setup>
import { date } from 'quasar';

import { onMounted, ref } from 'vue';
import { storeToRefs } from 'pinia';

import { ihmStore } from 'src/stores/ihm';
const IhmModule = ihmStore();

import { userStore } from 'src/stores/users';
const userModule = userStore();

import { logout } from 'src/api/users';

const { connected } = storeToRefs(userModule);
const dateJour = ref<string>();
const tab = ref('home');

const categorie = ref(['Google', 'Facebook', 'Twitter', 'Apple', 'Oracle']);
const catFilters = ref();
const icon = ref(false);
const mesEvt = ref(false);

onMounted(() => {
  console.log('state', connected);
  // recupération de la date
  const timeStamp = Date.now();
  dateJour.value = date.formatDate(timeStamp, 'DD/MM/YYYY');
});

//
// déconnexion de l'utilisateur
async function deconnecter() {
  // appel à l'api pour déconnecter l'utilisateur
  let response = null;
  try {
    IhmModule.startWaiting();
    response = await logout();
    IhmModule.stopWaiting();
    // on valide le deconnexion
    userModule.refreshConnected();
    //
    // message de déconnexion
    IhmModule.displayInfo({ code: 'CXOD' });
  } catch (error: any) {
    IhmModule.displayError({ code: 'CXCK', param: response.message });
    return null;
  }
}
</script>

<style scoped>
.button:hover {
  background-color: rgb(129, 12, 12);
  transition: 0.7s;
}

.item_menu {
  color: rgba(97, 14, 14, 0.626);
}
.item_menu {
  color: rgba(97, 14, 14, 0.626);
}
.btn-active {
  background-color: rgb(129, 12, 12);
}

.fond_menu {
  background-color: rgb(218, 213, 213);
}
</style>
