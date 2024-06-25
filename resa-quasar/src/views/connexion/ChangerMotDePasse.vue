
<template>
    <q-card style="width: 50%" flat bordered class="card-login fixed-center vertical-middle">
      <q-card-section >
          <div class="text-center text-h6">Formulaire de changement du mot de passe </div>
          <div class="text-center text-subtitle2">utilisateur : {{ router.params.email }}</div>
        </q-card-section>
      <q-form @submit="modifierPwd" class="q-gutter-md">
        <q-card-section>
            <div class="col">
              <q-input v-model="dataForm.password.saisi" label="nouveau nouveau mot de passe"
                :rules="[checkRules.required, checkRules.password]" filled :type="showPassword ? 'text' : 'password'">
                <template v-slot:prepend>
                  <q-icon name="key" />
                </template>
                <template v-slot:append>
                  <q-icon :name="showPassword ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                    @click="showPassword = !showPassword" />
                </template>
              </q-input>
            </div>
            <div class="col">
              <q-input v-model="dataForm.password.confirmation" label="mot de passe de confirmation"
                :rules="[checkRules.required, checkRules.password]" filled :type="showPasswordRedo ? 'text' : 'password'">
                <template v-slot:prepend>
                  <q-icon name="key" />
                </template>
                <template v-slot:append>
                  <q-icon :name="showPasswordRedo ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                    @click="showPasswordRedo = !showPasswordRedo" />
                </template>
              </q-input>
            </div>
        </q-card-section>
        <q-card-actions class="justify-center">
          <q-btn type="submit" no-caps outline color="primary" flat>modifier</q-btn>
          <q-btn flat :to="{ name: 'accueil' }" no-caps color="secondary">Annuler</q-btn>
        </q-card-actions>
      </q-form>
    </q-card>
  </template>
  
  
  <script setup lang="ts">
  import { ref} from 'vue'
  import { ihmStore } from 'src/stores/ihm';
  import { type ApiType } from 'src/api/api_types';
  import {API_EnvoimodifierMdp} from 'src/api/users'
  import { type UserType, defaultUser } from 'src/types/users';
  import { useRoute } from 'vue-router'
  import { checkRules } from 'src/utils/config'
  
  
  
  const IhmModule = ihmStore()
  const router = useRoute()
  const dataForm = ref<UserType>(defaultUser)
  const showPassword = ref(false)
  const showPasswordRedo = ref(false)
  
  
  async function modifierPwd(){
    let reponse:ApiType = {status:false, data:[{name:''}], message:''}
    //
    // récupération d'un token
    try {
    if ( router.params.token && router.params.email) {
      dataForm.value.token = <string>router.params.token
      dataForm.value.email = <string>router.params.email
  
      reponse = await API_EnvoimodifierMdp(dataForm.value)
      if ( reponse.status){
        IhmModule.displayInfo({ code: 'CXPW' });
        router.push({ name: 'accueil' });
      } else {
        IhmModule.displayError({ code: 'CXCP', param: reponse.message });
      }
      console.log(reponse, reponse)
    } else {
      IhmModule.displayError({ code: 'CXPE' });
      router.push({ name: 'connexion' });
    }
  } catch (error) {
        let message = ''
        if (error instanceof Error) message = error.message
        else message = String(error)
        IhmModule.displayError({ code: 'CXCP', param: message });
        router.push({ name: 'accueil' });
      }
  }
  
  </script>
  
  <style>
  .card-login {
    background-color: rgba(61, 15, 15, 0.144);
  }
  </style>