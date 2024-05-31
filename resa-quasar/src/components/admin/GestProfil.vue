<template>
  <q-item v-if="connected" clickable :to="{ name: 'profil' }">
    <q-item-section avatar class="logoff">
          <q-icon  name="perm_identity" />
    </q-item-section>
    <q-item-section>connexion</q-item-section>
  </q-item>
  <q-item  v-if="connected" clickable v-ripple @click.prevent="loadModifPwd()">
      <q-item-section avatar class="logoff">
        <q-icon name="key" />
      </q-item-section>
      <q-item-section>Changer son mot de passe</q-item-section>
  </q-item>
  <q-item clickable v-ripple @click.prevent="deconnecter()">
    <q-item-section avatar class="logoff">
      <q-icon color="red" name="settings_power" />
    </q-item-section>
    <q-item-section>Déconnexion</q-item-section>
  </q-item>


</template>
<script setup lang="ts">
import { ihmStore } from 'src/stores/ihm';
import { type ApiType } from 'src/api/api_types';
import {  onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import {
  logout, readProfil,API_demandeModifierMdp
} from 'src/api/users';


const IhmModule = ihmStore()
const router = useRouter()

const userConnected = ref({name:'',email:''})
const  connected = ref(false)

onMounted(async () => {
  await getProfil()
})

//
// déconnexion de l'utilisateur
async function deconnecter() {
  // appel à l'api pour déconnecter l'utilisateur
  let response= null
  try {
    response = await logout()
    router.push({ name: 'connexion' })
  }
  catch (error: any) {
    IhmModule.displayError({ code: 'CXCK', param: response.message });
    return null
  }

}

//
// récupération du profil
async function getProfil() {
  let response:ApiType = {status:false, data:[{name:'',email:''}], message:''}
  try {
  response =  await readProfil()
  if ( response.status){
    if ( response.data )
      userConnected.value.name = response.data[0].name
      userConnected.value.email = response.data[0].email
      connected.value = true
  } else {
    connected.value = false
  }
  console.log('response',response)
} catch (error: any) {
    IhmModule.displayError({ code: 'CXCK', param: response.message });
    return null
  }
}


async function loadModifPwd(){
  let response:ApiType = {status:false, data:[{name:''}], message:''}
  try {
    // appel à l'API afin de demander une autorisation de changement de mot de passe
    // en retour un mail est envoyé à l'utilisateur
    IhmModule.startWaiting();
   response = await API_demandeModifierMdp({ email: userConnected.value.email});
   IhmModule.stopWaiting();
   IhmModule.displayInfo({ code: 'CXMM' , param:userConnected.value.email })
   console.log ('response', response)
 } catch (error: any) {
    IhmModule.displayError({ code: 'CXCK', param: response.message });
    return null
  }
}

</script>
<style scooped>
.logoff {
  flex: none
}
</style>
