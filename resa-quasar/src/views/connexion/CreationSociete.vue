<template>
  <v-card class="mx-auto login" max-width="500"><v-card-title> Création d'une société</v-card-title><v-card-text>
      <v-row>
        <v-col md="8" offset-md="2">
          <v-form>
            <v-text-field label="nom société" :rules="[rules.required]" tabindex="1" v-model="dataForm.nom" />

            <adrmanagement ref="adrManagement" :consult="false" :adresse="dataForm.adresse" />
            <v-spacer />
            <v-text-field tabindex="3" label="email de contact" v-model="dataForm.emailContact" />
            <v-text-field tabindex="4" v-model="dataForm.telephone" label="Téléphone"
              :rules="[rules.telephone]"></v-text-field>
          </v-form></v-col></v-row></v-card-text>
    <v-card-actions>
      <v-row>
        <v-col cols="6"><v-btn tabindex="7" @click="creationSociete" plain>Création</v-btn>
        </v-col>
        <v-col cols="6"><v-btn tabindex="8" plain :to="{ name: 'configuration' }">Annuler</v-btn>
        </v-col></v-row></v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import { ihmStore } from 'src/stores/ihm';
const dataForm = ref()

const IhmModule = ihmStore()






const rules = {
  required: (value: any) => !!value || 'Required.',
  telephone: (value: any) => {
    const pattern = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
    return pattern.test(value) || 'téléphone incorrect.';
  },
};


async function creationSociete() {
  IhmModule.setWaiting
  /*try {
    const retour = await SetSociete(dataForm.value);
    if (retour) {
      IhmModule.displayInfo({ code: "CXCS" });
    }
  } catch (error) {
    // on est sur des erreur
    IhmModule.displayError({
      code: "CXSE",
      param: (error as Error).message,
    });
    IhmModule.setWaiting
  }*/
}

</script>

<style></style>
