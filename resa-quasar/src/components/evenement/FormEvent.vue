<template>
  <q-card flat>
    <div class="q-pa-lg">
      <div class="row">
        <div class="col col-6">
          <div class="text-h4 q-mb-md">{{ typeAction }}</div>
        </div>
        <div class="col col-6"> <q-btn-toggle v-model="actif" :toggle-color="getStaetColor" :options="[
          { label: 'En ligne', value: true, slot: 'actif' },
          { label: 'Hors ligne', value: false, slot: 'inactif' },

        ]">
            <template v-slot:actif>
              <q-tooltip>Mise en ligne</q-tooltip>
            </template>

            <template v-slot:inactif>
              <q-tooltip>Mise hors ligne</q-tooltip>
            </template>

          </q-btn-toggle></div>
      </div>


      <div class="q-py-md">
        <programmation />
      </div>



      <q-form class="q-gutter-md">


        <imageManagement v-model="modelValue.image" :path="K_storageImageEvent" @changeFile="changeImage" />
        <div class="q-pt-md row">

          <div class="col-6 q-pr-md"> <q-input v-model="modelValue.titre" label="Nom évènement*" counter maxlength="50"
              hint="Name and surname" lazy-rules :rules="[val => val && val.length > 0 || 'Please type something']" />
          </div>
          <div class="col-6"> <q-select v-model="modelValue.organisateurId" :options="lstOrganisateur"
              label="Organisateur" />
          </div>
          <div class="col-12 q-pt-md">
            <q-input v-model="url" label="Lien vers site évènement">
              <template v-slot:prepend>
                <q-icon name="travel_explore" />
              </template>
            </q-input>
          </div>
          <div class="col-12  q-pt-md">
            <q-select v-model="categorie" multiple :options="options" use-chips stack-label label="Categories" />
          </div>
        </div>
        <div class="row q-mt-lg" v-show="!event">
          <div class="col-12">Gestion des participants</div>
          <div class="col-5 q-pr-lg">
            <q-item class="slider">
              <q-item-section avatar>
                Nb participants max
              </q-item-section>
              <q-item-section>
                <q-slider v-model="nbParticipant" :min="2" :max="nbMaxParticipants" :step="1" label
                  :label-value="nbParticipant + ' personnes'" label-always color="primary" switch-label-side
                  switch-marker-labels-side style="max-width: 600px" />
              </q-item-section>
              <q-item-section side>
                {{ nbMaxParticipants }}
              </q-item-section>
            </q-item>
          </div>
          <div class="col-auto q-pt-md q-ml-md">Inscription participants : </div>
          <div class="col-4  q-pt-sm">

            <q-option-group name="inscription" v-model="valid_participants" :options="typeValidation" color="primary"
              inline><q-tooltip>
                Permet ou pas une inscription automatique des participants
              </q-tooltip></q-option-group>
          </div>
        </div>



        <adrManagement v-model="adr" />

        <div class="text-subtitle1">Description</div>
        <q-editor v-model="description" style="min-width:1000px" min-height="7rem" />

        <div>
          <q-btn label="Valider" type="submit" @click="saveModif(true)" color="primary" />
          <q-btn label="Annuler" type="reset" @click="saveModif(false)" color="primary" flat class="q-ml-sm" />
        </div>
      </q-form>
    </div>
  </q-card>
</template>

<script setup lang="ts">


import { ref, computed } from 'vue'
import { evtStore } from 'src/stores/evenement'
import { ihmStore } from 'src/stores/ihm'
import { userStore } from 'src/stores/users'
import { getUsers } from 'src/api/configuration'
import programmation from 'src/components/evenement/ProgrammationEvt.vue'
import adrManagement from 'src/components/ihm/AdrManagementgouv.vue'
import imageManagement from 'src/components/ihm/ManageImage.vue'

import {
  K_storageImageEvent,
  K_imgDefaut,
  K_size_avatar
} from 'src/utils/config';




const evtModule = evtStore()
const ihmModule = ihmStore()
const userModule = userStore()
//
// récupération de la valeur d'origine
const props = defineProps(['modelValue'])

//
// gestion d'un event pour synchroniser avec la valeur d'appel
const emit = defineEmits(['fin_modif', 'update:modelValue'])


//
// permet de mettre à jour la props
const modelValue = computed({  // Use computed to wrap the object
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value)
  }
});

const event = ref(false)
const categorie = ref()
const options = ref([
  'Théathe', 'Restaurant', 'Sport', 'Détente'
])
const lstOrganisateur = ref(['paul', 'pierre', 'jacques'])
const adr = ref()
const description = ref('')
// gestion de l'upload d'image
const imageUpload = ref()
const url = ref('')
const organisateur = ref<any>([])
const actif = ref('')

const nbParticipant = ref(1)
const nbMaxParticipants = ref(20)
const typeValidation = ref([{ label: 'Par validation', value: 'valid' }, { label: 'Automatique', value: 'auto' }])
const valid_participants = ref(true)

const getStaetColor = computed(() => {
  return actif.value ? 'green' : 'red'
})
const typeAction = computed(() => {
  return event.value ? 'Evènement' : 'Sortie'
})

//
// Permet de récupérer les organisateur de la société courante
async function getOrganisateurListe() {
  const societeId = userModule.getSocieteId
  if (societeId) {
    // recupérattion des utilisateurs
    const result = await getUsers({ data: societeId });
    if (result.data)
      result.data.forEach(
        (item) => {
          organisateur.value.push({ 'id': item.id, 'label': item.nom)
        }
      )
  }
}
//
// permet de mettre à jour l'image en fonction du composant image

function changeImage(files: any) {
  imageUpload.value = files
}
//
// Permet de sauvegarder l'event
async function saveModif(action: boolean) {
  if (action) {
    // on recopie la nouvelle image si besoin
    const result = await evtModule.saveCurrentEvt(imageUpload.value)
    if (result) {
      let data = JSON.parse(result.message)
      console.log('image', data.image)
      modelValue.value.image = data.image
      ihmModule.displayInfo({ code: 'SAOK' });
    }
  }
  emit('fin_modif')
}


</script>
<style>
.slider_participants {
  padding: none
}
</style>
