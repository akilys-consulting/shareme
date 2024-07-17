<template>
  <q-card flat>
    <q-card-section horizontal>
      <q-card-section class="col-5 flex flex-center">
        <displayImgCategorie
          :titre="currentEvt.titre"
          :categorieTab="currentEvt.categories"
          :display="displayCategorie.page"
      /></q-card-section>
      <q-card-section class="col-7">
        <q-card-section class="text-h5">
          {{ currentEvt.titre }}
        </q-card-section>
        <q-separator inset />
        <q-card-section class="text-subtitle2">
          Organisateur : {{ convertAuteur }}
        </q-card-section>

        <q-separator inset />
        <q-card-section class="text-subtitle2">
          Catégories : {{ convertCategorie }}
        </q-card-section>

        <q-separator inset />
        <q-card-section class="text-body1">
          {{ currentEvt.description }}
        </q-card-section>
        <div id="map" class="mapSize"></div>
      </q-card-section>
    </q-card-section>
  </q-card>
</template>
<script setup lang="ts">
import { ref, onBeforeMount, onMounted, computed } from 'vue';

import { EvenementType, evenementVide } from 'src/types/evenements';
import { displayCategorie } from 'src/types/ihm';

import { useRouter } from 'vue-router';
const router = useRouter();

import { getCurrentEvt } from 'src/utils/cookie';

import displayImgCategorie from 'src/components/ihm/LoadImgCategorie.vue';
import leaflet from 'leaflet';
import 'leaflet/dist/leaflet.css';
const currentEvt = ref<EvenementType>(evenementVide);

onBeforeMount(() => {
  currentEvt.value = getCurrentEvt();
  if (typeof currentEvt.value == 'undefined') {
    currentEvt.value = evenementVide;
    router.push({ name: 'accueil' });
  }
});

onMounted(() => {
  let mymap;
  mymap = leaflet.map('map').setView([51.505, -0.09], 13);

  leaflet;
  leaflet
    .tileLayer(
      'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',
      {
        maxZoom: 19,
        attribution:
          '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      }
    )
    .addTo(mymap);
  let marker = leaflet.marker([51.5, -0.09]).addTo(mymap);
});

const convertAuteur = computed(() => {
  if (currentEvt.value.auteur.name)
    return currentEvt.value.categories.toString();
  else return '';
});

const convertCategorie = computed(() => {
  return currentEvt.value.categories.toString();
});
</script>
<style scoped>
.mapSize {
  height: 300px !important;
  width: 100% !important;
}
</style>
