<!-- eslint-disable vue/multi-word-component-names -->

<template>
  <q-card style="width: 50%" flat bordered class="card-login fixed-center vertical-middle">
    <div v-if="displayMessage" class="q-py-lg">
    <q-card-section class="justify-center">
        <div class="text-h6">Demande nouveau mot de passe</div>
      </q-card-section>
    <q-card-section>
      <div class="text-subtitle2 q-px-md">Un mail a été envoyé à l'adresse {{ email }}. Ouvrez-le et cliquez sur le bouton pour pouvoir définir un nouveau mot de passe</div>
    </q-card-section>
  </div>
    <q-form v-else @submit="loadModifPwd" class="q-gutter-md">
      <q-card-section>
        <div class="text-h6">Demande nouveau mot de passe</div>
        <div class="text-subtitle2">la demande vous sera envoyée par mail</div>
      </q-card-section>
      <q-card-section>
        <div class="column">
          <div class="col">
            <q-input filled v-model="email" lazy-rules :rules="[checkRules.required, checkRules.email]"
              label="Votre email">
              <template v-slot:prepend>
                <q-icon name="person" />
              </template>
            </q-input>
          </div>
        </div>
      </q-card-section>
      <q-card-actions class="justify-center">
        <q-btn type="submit" outline color="primary" flat>nouveau mot de passe</q-btn>
        <q-btn flat :to="{ name: 'connexion' }" color="secondary">Annuler</q-btn>
      </q-card-actions>
    </q-form>

  </q-card>
</template>

<script setup lang="ts">

import { checkRules } from 'src/utils/config'

import { ref } from 'vue'
const email = ref()
const displayMessage = ref(false)


import { useRouter } from 'vue-router'
const router = useRouter()

import { ihmStore } from 'src/stores/ihm';
const IhmModule = ihmStore()

import { API_demandeModifierMdp } from 'src/api/users';
import { type ApiType } from 'src/api/api_types';

//
// function permettant d'envoyer un email à l'utilisateur
// pour qu'il puisse modifier son mot de passe
async function loadModifPwd(){
  let response:ApiType = {status:false, data:[{name:''}], message:''}
  try {
    // appel à l'API afin de demander une autorisation de changement de mot de passe
    // en retour un mail est envoyé à l'utilisateur
    IhmModule.startWaiting();
   response = await API_demandeModifierMdp({ email: email.value});
   IhmModule.stopWaiting();
   displayMessage.value = !displayMessage.value

 } catch (error: any) {
    IhmModule.displayError({ code: 'CXCK', param: response.message });
    return null
  }
}


</script>

<style>
.login {
  margin-top: 15%;
  opacity: 0.8;
}

.q-field__inner {
  background-color: rgba(94, 26, 26, 0.178) !important;
}

.card-login {
  background-color: rgba(158, 108, 106, 0.123);
}


.icon_image {
  width: auto;
  height: auto;
  max-height: 2em;
  max-width: 2em;
  margin-top: -2px;
  margin-right: 10px;
}

.button:hover {
  background-color: rgb(129, 12, 12);
  transition: 0.7s;
}

.btn-active {
  background-color: rgb(129, 12, 12);
}
</style>

