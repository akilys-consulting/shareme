<template>
  <div>
    <choixsociete ref="defineSociete" @changesociete="refreshList"></choixsociete>
  </div>
  <div>

    <v-data-table :headers="headers" :items="listUser" sort-by="nom" class="elevation-1">
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Gestion des utilisateurs</v-toolbar-title>

          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
              <v-btn color="success" fab small v-on="on">
                <v-icon dark>mdi-plus</v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline"></span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="12" md="12">
                      <v-select :items="tabProfil" item-text="value" item-value="key" return-object label="Profil"
                        multiple v-model="editedItem.profil" dense></v-select>
                    </v-col>
                    <v-col cols="12" sm="12" md="12">
                      <v-text-field v-model="editedItem.nom" :rules="[rules.required]" label="Nom"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="12">
                      <v-text-field v-model="editedItem.prenom" label="Prénom"></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="12" md="12">
                      <v-text-field v-model="editedItem.email" label="Email" :rules="[rules.email]"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="12" md="12">
                      <v-text-field v-model="editedItem.nomAffiche" label="Nom affiché"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Annuler</v-btn>
                <v-btn color="blue darken-1" text @click="saveItem">sauver</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.action="{ item }">
        <v-icon mall class="mr-2" @click="initMdp(item)">mdi-lock-reset</v-icon>
        <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil-outline</v-icon>
        <v-icon color="error" small class="mr-2" @click="deleteItem(item)">mdi-delete-outline</v-icon>

        <question ref="validationSupp"></question>
      </template>

    </v-data-table>
  </div>
</template>

<script setup lang="ts">

import { ref, computed, onMounted } from 'vue'
import { getUsers, delUser, setDataUser } from 'src/api/configuration';
import { updateMdp } from 'src/api/users';
import { type UserType, defaultUser, profilList } from 'src/types/users';
import { type messageType } from 'src/types/ihm';
import { userStore } from 'src/stores/users';
import { ihmStore } from 'src/store/ihm';
import { useRouter } from 'vue-router'
import { K_InitMdp, rules } from 'src/utils/config';
import choixsociete from '@/components/admin/ChoixSociete.vue';

const IhmModule = ihmStore()
const connexionModule = userStore()
const router = useRouter()

const lstsociete = ref<any>(null)
const validationSupp = ref<any>(null)



//
// récupération des différents profils disponibles
// select sur les profil
const tabProfil = computed(() => {
  console.log('list profil', profilList);
  return profilList;
})

const dialog = ref(false)

const listUser = ref<UserType[]>([])
const editedItem = ref<UserType>(defaultUser)
const defaultItem: UserType = defaultUser;
let editedIndex = -1;

let currentSocieteId = -1;


// définiton des colonnes du tableau des utilisateurs
const headers = [
  { title: 'Nom', key: 'nom', sortable: true },
  { title: 'Prenom', key: 'prenom', sortable: false },
  { title: 'Email', key: 'email', sortable: false },
  { title: 'Actions', key: 'action', sortable: false },
]

onMounted(() => {
  // on récupère la société sélectionnée
  const currentSocieteId = lstsociete.value.currentSocieteId
  // récupération des users de la société
  getData(currentSocieteId);
})

async function getData(societeId: number) {
  //
  // on charge les différents profils

  // récupération des users de la société sélerctionnée dans choixSOciete
  const { data, status, message } = await getUsers({
    societeId: societeId,
  });
  if (status) {
    listUser.value = data;
  } else {
    IhmModule.displayError({ code: 'ADMIN', param: message });
  }
}
//
// on rafraichit la liste sur le changement de la société
// réception de l'évènement changesociete
function refreshList() {
  currentSocieteId = lstsociete.value.currentSocieteId;
  getData(currentSocieteId)
}
//
// gestion de la modification d'un utilisateur
function editItem(item: UserType) {
  editedIndex = listUser.value.indexOf(item);
  editedItem.value = Object.assign({}, item);
  dialog.value = true;
}
//
// suppression d'un utilisateur
function deleteItem(item: UserType) {
  let question: messageType = {
    code: 'QEDE',
    param: item.nom,
    display: true,
  };
  validationSupp.value.open(question).then((response: boolean) => {
    if (response) {
      confirmedelete(item);
    } else {
      // on fait rien
    }
  });
}

async function confirmedelete(item: UserType) {
  let index = listUser.value.indexOf(item);

  //
  // suppression en base
  const { status, message } = await delUser({ id: item.id });
  if (status) {
    listUser.value.splice(index, 1);
  } else {
    IhmModule.displayError({ code: 'ADMIN', param: message });
  }
}

function close() {
  editedItem.value = Object.assign({}, defaultItem);
  editedIndex = -1;
  dialog.value = false;
}

//
// sur un nouvel enregistrement,
// le mot de passe a une valeur prédéfini
// la société est positionné à la société courante
function saveItem() {
  editedItem.value.societe_id = currentSocieteId;
  if (editedIndex == -1) {
    editedItem.value.password = K_InitMdp;
  }

  //
  // sauvegarde de l'utilisateur en base
  setDataUser(editedItem.value)
    .then((response) => {
      if (response.status) {
        IhmModule.displayInfo({ code: 'SAOK' });
        // on regarde si l'utilisateur est l'utilisateur connecté
        if (connexionModule.getUserId == editedItem.value.id) {
          connexionModule.setUser(editedItem.value);
        }
        if (editedIndex == -1) {
          listUser.value.push(editedItem.value);
        } else {
          Object.assign(listUser.value[editedIndex], editedItem.value);
        }
      } else IhmModule.displayError({ code: 'ADMIN', param: response.message });
      self.close();
    })
    .catch((error) => {
      IhmModule.displayError({
        code: 'ADMIN',
        param: (error as Error).message,
      });
      self.close();
    });
}
//
//
// permet de reinitialiser le mot de passe d'un utilisateur
async function initMdp(item: UserType) {
  if (item.id) {
    const dataItem = Object.assign({}, item);
    // initialisation du mot de passe
    dataItem.password = K_InitMdp;
    const { status, message } = await updateMdp(dataItem);
    if (status) {
      IhmModule.displayInfo({ code: 'CXGW' });
      // on regarde si l'utilisateur est l'utilisateur connecté
      if (connexionModule.getUserId == item.id) {
        connexionModule.deconnexion();
        // onaffiche la page d'accueil
        router.push({ name: 'connexion' }).catch(() => { });
      }
    } else IhmModule.displayError({ code: 'ADMIN', param: message });
  }
}
</script>
