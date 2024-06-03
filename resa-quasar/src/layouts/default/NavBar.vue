
<template>
  <q-header class="bg-white q-py-md text-primary text-color ">
    <q-toolbar  flat class="q-mr-sm">
      <q-toolbar-title >ShareMe<q-separator /></q-toolbar-title>
      <q-toolbar-title  class="text-center">
        <q-btn outline :color="connected?'green':'red'" round size="20px" :icon="connected?'record_voice_over':'person'" class="q-pa-sm q-ma-sm">
        <q-menu > 
          <q-list class="fond_menu" style="min-width: 100px">

            <q-item v-if="connected" clickable @click="deconnecter" v-close-popup>
              <q-item-section avatar>
                <q-icon color="green" name="record_voice_over" />
              </q-item-section>
              <q-item-section>deconnexion</q-item-section>
            </q-item>
            <q-item v-else clickable :to="{ name: 'connexion' }" v-close-popup>
              <q-item-section avatar>
                <q-icon color="primary" name="person" />
              </q-item-section>
                <q-item-section>connexion</q-item-section>
            </q-item>
            <q-separator />
            <q-item clickable  v-close-popup>
              <q-item-section avatar>
                <q-icon color="primary" name="badge" />
              </q-item-section>
                <q-item-section>Votre profil</q-item-section>
            </q-item>
            <q-separator />
            
            <q-item clickable v-close-popup>
              <q-item-section avatar>
                <q-icon color="primary" name="tune" />
              </q-item-section>
              <q-item-section>filtrer évènements</q-item-section>
            </q-item>
            <q-separator />
            <q-item clickable v-close-popup>
              <q-item-section>Recent tabs</q-item-section>
            </q-item>
          </q-list>
        </q-menu>
      </q-btn>
        
      </q-toolbar-title>
      <q-toolbar-title class="text-right">{{ dateJour }} <q-separator /></q-toolbar-title>
    </q-toolbar>
  </q-header>

</template>


<script lang="ts" setup>
import { date } from 'quasar'


import { onMounted,ref} from 'vue'
import { storeToRefs } from 'pinia'

import { ihmStore } from 'src/stores/ihm';
const IhmModule = ihmStore()

import { userStore } from 'src/stores/users';
const userModule = userStore()

import {
  logout
} from 'src/api/users';

const { connected } = storeToRefs(userModule)
const dateJour = ref<string>()


onMounted(() => {
  console.log('state',connected)
  // recupération de la date
  const timeStamp = Date.now()
  dateJour.value =date.formatDate(timeStamp, 'DD/MM/YYYY')
})

//
// déconnexion de l'utilisateur
async function deconnecter() {
  // appel à l'api pour déconnecter l'utilisateur
  let response= null
  try {
    IhmModule.startWaiting();
    response = await logout()
    IhmModule.stopWaiting();
    // on valide le deconnexion
    userModule.refreshConnected()
    //
    // message de déconnexion
    IhmModule.displayInfo({ code: 'CXOD' });
  }
  catch (error: any) {
    IhmModule.displayError({ code: 'CXCK', param: response.message });
    return null
  }

}
</script>

<style scoped>
.button:hover {
  background-color: rgb(129, 12, 12);
  transition: 0.7s;
}

.btn-active {
  background-color: rgb(129, 12, 12);
}

.fond_menu {
  background-color: rgba(158, 108, 106, 0.123);
}

</style>
