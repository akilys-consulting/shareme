<template>
  <q-img :class="classObject" :src="getPathImg">
    <div class="absolute-bottom text-subtitle1 text-center">
      {{ titre }}
    </div></q-img
  >
</template>
<script setup lang="ts">
import { computed } from 'vue';
import { categoriesType } from 'src/types/evenements';
import {displayCategorie} from 'src/types/ihm'

const data = defineProps<{
  categorieTab: categoriesType;
  titre?: string;
  display: displayCategorie;
}>();

const classObject = computed(() => ({
  'img-size-tab': data.display === displayCategorie.tab,
  'img-size-page': data.display === displayCategorie.page,
}));

function strNoAccent(chaine: string) {
  return chaine.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

// On récupère la première catégorie pour définir l'image
// si pas de categorie => image inconnue
const getPathImg = computed(() => {
  let pathImage = '';
  if (typeof data.categorieTab != 'undefined' && data.categorieTab.length > 0) {
    pathImage =
      '/categories/' + strNoAccent(data.categorieTab[0]).toLowerCase() + '.jpg';
  } else {
    pathImage = '/icons/image_inconnue.png';
  }
  return pathImage;
});
</script>
<style scoped>
.img-size-tab {
  max-height: 200px !important;
  max-width: 300px !important;
}
.img-size-page {
  max-height: 100% !important;
  max-width: 100% !important;
}
</style>
