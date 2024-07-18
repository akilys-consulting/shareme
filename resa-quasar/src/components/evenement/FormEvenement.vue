<template>
  <q-card flat>
    <form @submit="updateEvenement" ref="form">
      <div class="q-pa-lg">
        <div v-if="isPro" class="col col-12">
          <div class="row q-pb-md">
            <div class="col col-auto q-mr-xl q-mt-sm">Mise en ligne</div>
            <div class="col col-auto">
              <q-btn-toggle
                v-model="modelValue.actif"
                :toggle-color="getEtatEvenement"
                size="sm"
                :options="[
                  { label: 'En ligne', value: true, slot: 'actif' },
                  { label: 'Hors ligne', value: false, slot: 'inactif' },
                ]"
              >
                <template v-slot:actif>
                  <q-tooltip>Mise en ligne</q-tooltip>
                </template>

                <template v-slot:inactif>
                  <q-tooltip>Mise hors ligne</q-tooltip>
                </template>
              </q-btn-toggle>
            </div>
          </div>
        </div>
        <div class="col-12 q-pr-md q-pb-md">
          <q-input
            bottom-slots
            v-model="modelValue.titre"
            label="Nom évènement*"
            counter
            maxlength="50"
            lazy-rules
            :rules="[checkRules.required]"
          >
            <template v-slot:hint> nb caractères : </template></q-input
          >
        </div>
        <div class="q-py-md">
          <q-card class="q-pa-md">
            <gestionImage
              v-if="isPro"
              :modelValue="modelValue.image"
              :acceptUpload="true"
              @update:modelValue="miseAJourImage"
              @changeFile="chargerFichierImage"
            ></gestionImage
          ></q-card>
        </div>
        <div class="q-py-md">
          <programmation
            :progEvt="modelValue.recurrence"
            @ForceReccurence="ReccurenceSimple"
          />
        </div>
        <q-form class="q-gutter-md">
          <div class="q-pt-md row">
            <div class="col-12 q-pt-md">
              <q-input
                v-model="modelValue.url"
                label="Lien vers site évènement"
              >
                <template v-slot:prepend>
                  <q-icon name="travel_explore" />
                </template>
              </q-input>
            </div>
            <div class="col-12 q-pt-md">
              <q-select
                v-model="modelValue.categories"
                multiple
                :options="objetCategories"
                use-chips
                stack-label
                error-message="la catégorie est obligatoire"
                :error="!categorieValid"
                label="Categories"
              />
            </div>
          </div>
          <!-- saisi adresse de l'évènement -->
          <adrManagement v-model="modelValue.adresse" />

          <div class="text-subtitle1">Description</div>
          <q-editor
            v-model="modelValue.description"
            style="min-width: 1000px"
            min-height="7rem"
          />

          <div>
            <q-btn label="Valider" @click="updateEvenement" color="primary" />
            <q-btn
              label="Annuler"
              type="reset"
              color="primary"
              flat
              class="q-ml-sm"
            />
          </div>
        </q-form>
      </div>
    </form>
  </q-card>
</template>

<script setup lang="ts">
import { ref, onBeforeMount, computed } from 'vue';

//
// import des types
import {
  evenementVide,
  listCategories,
  type categoriesType,
} from 'src/types/evenements';
import {
  ProgrammationType,
  programmationParDefaut,
} from 'src/types/programmation_evt';
//
// import des stores
import { ihmStore } from 'src/stores/ihm';
const IhmModule = ihmStore();
import { useRouter } from 'vue-router';
import { userStore } from 'src/stores/users';
const userModule = userStore();

import { checkRules } from 'src/utils/config';

//
// import du router
const router = useRouter();
//
// import des fonctions spécifiques
import { getCurrentEvt } from 'src/utils/cookie';
//
// import des composants
import adrManagement from 'src/components/ihm/AdrManagementgouv.vue';
import gestionImage from 'src/components/ihm/ManageImage.vue';
import programmation from 'src/components/evenement/ProgrammationEvt.vue';

import { saveEvenement } from 'src/api/evenement';
import { copieImage } from 'src/api/ihm';

const form = ref<HTMLInputElement | null>(null);
const progEvt = ref<ProgrammationType[]>(programmationParDefaut);
const modelValue = ref(evenementVide);
const isPro = ref(false);
const objetCategories = ref<categoriesType>(listCategories);

onBeforeMount(() => {
  isPro.value = userModule.getIsPro;

  modelValue.value = getCurrentEvt();
  if (typeof modelValue.value == 'undefined') {
    modelValue.value = evenementVide;
    router.push({ name: 'accueil' });
  } else {
    progEvt.value = modelValue.value.recurrence;
  }
});
//
// permet de contrôler que la catégorie n'est pas vide
const categorieValid = computed(() => modelValue.value.categories.length > 0);

const getEtatEvenement = computed(() => {
  if (typeof modelValue.value != 'undefined') {
    return modelValue.value.actif ? 'green' : 'red';
  }
  return 'red';
});

function ReccurenceSimple(data: ProgrammationType[]) {
  modelValue.value.recurrence = data;
}

function miseAJourImage(path: string) {
  modelValue.value.image = path;
}

//
// appelé par l'emit du componsant ManageImage
// il recoit l'image uploadée et retaillée
// on appel l'API Laravel pour downloader l'image
// on mémroise dans l'évènement courant le nom de l'iamge
async function chargerFichierImage(ImgBase64: string) {
  const response = await copieImage(ImgBase64);
  if (response.status && response.data)
    modelValue.value.image = response.data[0].fichier;
}
//
// Mise à jour de l'évènement
async function updateEvenement() {
  // on contrôle que la catégorie est renseignée
  // le lazy-rules ne fonctionne pas sur les q-select
  if (categorieValid.value) {
    console.log('evenement', modelValue.value);
    const response = await saveEvenement(modelValue.value);
    if (response.status) {
      IhmModule.displayInfo({ code: 'SAOK' });
    }
    console.log('reponse', response);
  }
}
</script>
<style>
.slider_participants {
  padding: none;
}
</style>
