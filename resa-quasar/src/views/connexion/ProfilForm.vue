<template>
  <div>
    <v-btn v-if="!isAuthenticated" icon :to="{ name: 'connexion' }"><v-icon>mdi-account-arrow-right-outline </v-icon>
    </v-btn>
    <v-menu v-else v-model="displayProfil" :close-on-content-click="false" nudge-width="400" max-width="400" offset-x>
      <template v-slot:activator="{ on }">
        <v-avatar @click="getUser" size="36" v-on="on" color="primary"><v-icon dark>mdi-account</v-icon></v-avatar>
      </template>
      <v-card>
        <v-toolbar>
          <v-spacer></v-spacer>
          <deconnexion />
          <v-tooltip bottom>
            <template v-slot:activator="{ display }">
              <v-btn @click="saveProfil" icon v-on="display">
                <v-icon>mdi-content-save-outline</v-icon>
              </v-btn>
            </template>
            <span>sauvegarder</span>
          </v-tooltip>
        </v-toolbar>

        <v-tabs v-model="tab" show-arrows>
          <v-tab>profil</v-tab>
          <v-tab>connexion</v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
          <v-tab-item>
            <v-card-text>
              <v-form ref="form" lazy-validation>
                <v-row>
                  <v-col cols="6">
                    <v-text-field v-model="user.nom" label="Nom" required :rules="[rules.required]"></v-text-field>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field v-model="user.prenom" label="Prénom" required :rules="[rules.required]"></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field v-model="user.nomAffiche" label="Nom affiché"></v-text-field>
                  </v-col>
                </v-row> </v-form></v-card-text>
          </v-tab-item>
          <gestCpt />
        </v-tabs-items> </v-card></v-menu>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

import { defaultUser, type UserType } from 'src/types/users';

import { userStore } from 'src/stores/users';
import { ihmStore } from 'src/stores/ihm';

const IhmModule = ihmStore()
const connexionModule = userStore()

/*import compteManagement from '@/components/CompteManagement'*/
import deconnexion from 'src/components/users/Deconnexion.vue';
import gestCpt from 'src/components/users/CompteManagement.vue';



const tab = ref(0)
const displayProfil = ref(false)
const rules = { required: (value: string) => !!value || 'obligatoire.' };
const user = ref<UserType>(defaultUser)

const isAuthenticated = computed(() => {
  return connexionModule.isAuthenticated();
})

async function saveProfil() {
  const response = await connexionModule.updateUser(user.value);
  if (response) {
    IhmModule.displayInfo({ code: 'SAOK' });
  }
}

function getUser(): void {
  const data: any = connexionModule.getUser
  user.value = data
}


</script>

<style></style>
