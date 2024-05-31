<template>
  <q-toolbar class="bg-primary text-white">
    <q-btn round to="/appli/accueil">
      <q-icon name="arrow_back" />
    </q-btn>
    <q-toolbar-title>
      {{ $t('texte.FPTI') }}
    </q-toolbar-title>
  </q-toolbar>

  <q-card>
    <q-form class="q-ma-md">

      <imageManagement v-model="user.avatar" :path="K_storageAvatarUser" @changeFile="changeImage" />
      <div class="row q-mt-lg">
        <q-input class="col-6" v-model="user.nom" :label="$t('texte.FPNM')">
        </q-input>
        <q-input class="col-6  q-pl-lg" v-model="user.prenom" :label="$t('texte.FPPN')">
        </q-input>
      </div>
      <div class="row">

        <q-input class="col-6" v-model="user.nomAffiche" :label="$t('texte.FPNA')">
        </q-input>
        <q-input class="col-6   q-pl-lg" v-model="user.email" :label="$t('texte.FPAM')" :rules="[
          (val) => validateEmail(val) || $t('texte.FPCM')
        ]">
        </q-input>
      </div>

      <div class="row">
        <q-input class="col-6" v-model="password" :type="isPwd ? 'password' : 'text'" :label="$t('texte.FPMP')">
          <template v-slot:append>
            <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer" @click="isPwd = !isPwd" />
          </template>
        </q-input>
        <q-input class="col-6  q-pl-lg" v-model="passwordCheck" type="password" :label="$t('texte.FPMC')" :rules="[
          (val) => validatePassword(val) || $t('texte.FPCM')
        ]">
        </q-input>
      </div>

      <adr v-model="adrUser" />

      <div class="row q-mt-lg">
        <div class="col-12">
          <q-btn :label="$t('texte.FGSR')" type="submit" color="secondary" flat @click="modifierProfil" />
          <q-btn :label="$t('texte.FGAN')" type="reset" color="primary" flat class="q-ml-sm" />
        </div>
      </div>

    </q-form>
  </q-card>
</template>
<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

import { UserType } from 'src/types/users'
import { userStore } from 'src/stores/users';
import { ihmStore } from 'src/stores/ihm';
import { K_storageAvatarUser } from 'src/utils/config'

import { setDataUser } from 'src/api/configuration'

const connexionModule = userStore()
const ihmModule = ihmStore()
const router = useRouter()


const user = ref<UserType>(connexionModule.getUser())
let avatarObj: Blob

import imageManagement from 'src/components/ihm/ManageImage.vue'

const password = ref('')
const passwordCheck = ref('')
const adrUser = ref()
const isPwd = ref(true)


onMounted(async () => {
  if (user.value.id) {
    const test = await connexionModule.getAvatarUser(user.value.id)
    console.log('test', test)
    user.value.avatar = test.avatar
  }
})
function validateEmail(email: string): boolean {
  return /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(email);
}

function validatePassword(newPassword: string): boolean {
  return password.value == newPassword;
}

function changeImage(files: Blob) {
  avatarObj = files
}

//
// Fonction de sauvegarde du profil
// il faur gérer aussi l'image avatar si elle a chnagée
async function modifierProfil() {
  // on envoie les données à la base
  const result = await setDataUser(user.value, avatarObj)
  // contrôle si la gestion DB s'est bien passée
  if (result.status) {
    let data = JSON.parse(result.message)
    //
    // on met à jour les données en focntion de la sauvegarde
    if (avatarObj)
      user.value.avatar = data.image
    user.value.id = data.id
    //
    // gestion de la modification du mot de passe
    if (password.value) {
      await connexionModule.updateMdpUser(password.value)
      router.push({ name: 'connexion' })
    }
    ihmModule.displayInfo({ code: 'SAOK' });
  } else {
    ihmModule.displayError({ code: 'CXUU', param: result.message });
  }
}
</script>
<style scoped></style>
