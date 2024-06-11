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
