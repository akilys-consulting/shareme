<template>
  <q-card v-show="!modif">
    <q-card-actions align="right">
      <q-btn icon="mode_edit" @click="modif = !modif">Modifier</q-btn>
    </q-card-actions>

    <q-card-section horizontal>
      <q-img class="img-size col-4" :src="imageDisplayed" />
      <q-card-section>
        <q-card-section>
          <div class="text-h4 col-12">
            {{ evt_data.titre }}
          </div><br>

          <div class="text-h6 col-12">
            Organisateur :
            <q-avatar class="shadow-2" size="xl">
              <img :src="avatar">
            </q-avatar>
            {{ nomAffiche }}

          </div>
        </q-card-section>

        <q-separator />
        <q-card-section>
          <div class="text-h6">
            Catégorie : {{ displayCat }}
          </div>
        </q-card-section>

        <q-separator />
        <q-card-section>
          <div class="text-body1">
            {{ evt_data.description }}
          </div>
        </q-card-section>
      </q-card-section>

    </q-card-section>
    <div class="q-ma-sm" id="map"></div>
  </q-card>
  <frmEvt v-model="evt_data" v-show="modif" @fin_modif="modif = false" />
</template>
<script setup lang="ts">
import 'leaflet/dist/leaflet.css';
import { onMounted, ref, computed } from 'vue'
import leaflet from 'leaflet';

import { evtStore } from 'src/stores/evenement'
import { userStore } from 'src/stores/users'

import {
  K_storageImageEvent,
  K_imgDefaut,
} from 'src/utils/config';
import frmEvt from 'src/components/evenement/FormEvent.vue'
import router from 'src/router';
//
// récupération de la valeur d'origine
const evtModule = evtStore()
const userModule = userStore()
//
// on charge les datas de l'évènement à afficher
let evt_data = evtModule.getCurrentEvt

const avatar = ref(K_storageImageEvent + K_imgDefaut)
const nomAffiche = ref('')

const modif = ref(false)

// si aucun évènement n'est en mémoire, on revient à la liste des évènements
if (evt_data.id < 0)
  router.push({ name: 'accueil' })

// on termine l'affichage
//  chargement dfe la carte
//  affichage de l'image
onMounted(async () => {
  // on charge les données
  let mymap;
  mymap = leaflet.map('map').setView([51.505, -0.09], 13);

  leaflet.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  }).addTo(mymap);
  leaflet.marker(
    [51.5, -0.09]).addTo(mymap);

  //
  // manage avatar
  const result = await displayAvatar()
  if (result) {
    avatar.value = result.avatar
    nomAffiche.value = result.nomaffiche
  }
});

// on affichage sous forme de chaine les catégories sélectionnées pour l'évènment
const displayCat = computed(() => {
  return evtModule.getCategoriesString
})

//
// on affichage l'image soit on prend l'image si elle existe soit on prend l'image par défaut
const imageDisplayed = computed(() => {
  if (!evt_data.image)
    return K_storageImageEvent + K_imgDefaut;
  return evt_data.image
})

//
// gestion de l'affichage de l'avatar de l'organisateur
async function displayAvatar(): Promise<{ avatar: string, nomaffiche: string }> {
  if (evt_data.organisateurid && evt_data.organisateurid > 0) {
    const response = await userModule.getAvatarSociete(evt_data.organisateurid)
    if (response) {
      if (!response && !response.avatar) return { avatar: K_storageImageEvent + K_imgDefaut, nomaffiche: response.nomaffiche };
      else return response;
    } else {
      return { avatar: K_storageImageEvent + K_imgDefaut, nomaffiche: 'Inconnu' }
    }
  }
}
</script>

<style scoped>
.img-size {
  max-height: 400px !important;
  max-width: 400px !important;
}

#map {
  height: 600px;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
}
</style>
