<template>
  <q-dialog v-model="editForm" persistent>
    <q-card class="frm-edit">
      <q-card-sections>
        <q-form class="q-pa-sm" align="right">
          <q-input class="q-my-xs" label="nom" v-model="editedItem.nom" counter />
          <q-input class="q-my-xs" label="email contact" :rules="[val => !!val || 'Email is missing', isValidEmail]"
            v-model="editedItem.email_contact" />
          <q-checkbox left-label label="actif" class="q-my-xs" v-model="editedItem.actif" />
          <imageManagement v-model="editedItem.avatar" :path="K_storageAvatarSociete" @changeFile="changeImage" />
          <gestionAdr v-model="editedItem.adresse" />
        </q-form>
      </q-card-sections>
      <q-card-actions align="right">
        <q-btn label="Sauver" outline color="primary" @click="sauvegarder" v-close-popup />
        <q-btn label="Fermer" outline color="warning" @click="editForm = false" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>




  <q-dialog v-model="confirmDel" persistent>
    <q-card>
      <q-card-section class="row items-center">
        <q-avatar icon="delete_forever" color="negative" text-color="white" />
        <span class="q-ml-sm">Merci de confirmer la suppression de la société : {{ editedItem.nom }}</span>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Confirmer" color="primary" @click="deleteRowConfirmed" v-close-popup />
        <q-btn flat label="Annuler" color="primary" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>

  <q-table title="Liste des sociétés" :rows="listSociete" :columns="headers">
    <template v-slot:top>
      <q-btn color="primary" icon="add" :label="$t('texte.FGAJ')" @click="ajouterRow">
        <q-tooltip>Ajouter une donnée</q-tooltip></q-btn>
      <q-btn class="q-ml-sm" icon="save" color="primary" :label="$t('texte.FGSR')" @click="sauvegarder">
        <q-tooltip>Enregistrer les données</q-tooltip></q-btn>
    </template>
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td key="nom" :props="props">
          {{ props.row.nom }}
        </q-td>
        <q-td key="email_contact" :props="props">
          {{ props.row.email_contact }}
        </q-td>
        <q-td key="actif" :props="props">
          <q-checkbox disable v-model="props.row.actif" />
        </q-td>
        <q-td key="delete" :props="props">
          <q-btn color="primary" icon-right="mode_edit" no-caps flat dense @click="ModifierRow(props.row)" />
          <q-btn color="negative" icon-right="delete" no-caps flat dense @click="deleterow(props.row)" />
        </q-td>
      </q-tr></template>
  </q-table>
</template>

<script setup lang="ts">

import { onMounted, ref } from 'vue'
import { getSocietes, updateSociete, delSociete } from 'src/api/configuration';
import { type SocieteType, defaultItem } from 'src/types/configuration';
import { onBeforeRouteLeave } from 'vue-router';

import {
  K_storageAvatarSociete,
} from 'src/utils/config';

import { ihmStore } from 'src/stores/ihm';
const IhmModule = ihmStore()

import gestionAdr from 'src/components/ihm/AdrManagementgouv.vue'
import imageManagement from 'src/components/ihm/ManageImage.vue'


const editForm = ref(false)
let currentIndex = 0


const confirmDel = ref(false)
const imageModified = ref()

const listSociete = ref<SocieteType[]>([])

const headers = [
  { label: 'Nom', name: 'nom', field: 'nom', sortable: false },
  { label: 'contact', name: 'email_contact', field: 'email_contact', sortable: false },
  { label: 'actif', name: 'actif', field: 'actif', sortable: false },
  { label: 'action', name: 'delete', field: '', sortable: false }

];

const editedItem = ref<SocieteType>(defaultItem)
const rules = {
  required: (value: string) => !!value || 'champ obligatoire.',
  email: (value: string) => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(value) || "ce n'est pas un e-mail";
  },
  telephone: (value: string) => {
    const pattern = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
    return pattern.test(value) || "ce n'est pas numéro de téléphone";
  },
};
onMounted(() => {
  initialize();
})

onBeforeRouteLeave((to, from, next) => {
  console.log('avant de quitter la route', to, from, next)
  next()
})

async function initialize() {
  // récupération des enregistrements existants
  const { data, status, message } = await getSocietes();
  if (status && data) {
    listSociete.value = data;
  } else {
    IhmModule.displayError({ code: 'ADMIN', param: message });
  }
}

function changeImage(files: any) {
  imageModified.value = files
}

function ModifierRow(row: any) {
  currentIndex = listSociete.value.findIndex(e => row && e.id == row.id);
  editedItem.value = listSociete.value[currentIndex]
  editForm.value = true
}

function deleterow(row: any) {
  editedItem.value = row
  confirmDel.value = true

}

async function deleteRowConfirmed() {
  confirmDel.value = false
  currentIndex = listSociete.value.findIndex(e => editedItem.value && e.id == editedItem.value.id);
  if (currentIndex) {
    const { status, message } = await delSociete({ id: listSociete.value[currentIndex].id });
    if (status) {
      listSociete.value.splice(currentIndex, 1)
    } else {
      IhmModule.displayError({ code: 'ADMIN', param: message });
    }
  }
}


function isValidEmail(val: string) {
  const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
  return emailPattern.test(val) || 'Invalid email';
}

function ajouterRow() {
  listSociete.value.push(defaultItem)
}
function sauvegarder() {

  updateSociete(editedItem.value, imageModified.value)
    .then(async (response) => {
      if (response.status) {
        // on recharge les data afin de récupérer l'id de la nouvelle société
        await initialize()
        IhmModule.displayInfo({ code: 'SAOK' });
      }
      else IhmModule.displayError({ code: 'ADMIN', param: response.message });
    })
    .catch((error) => {
      IhmModule.displayError({
        code: 'ADMIN',
        param: (error as Error).message,
      });
    });
  close();
}
</script>
<style>
.frm-edit {
  width: 100% !important;
}
</style>
