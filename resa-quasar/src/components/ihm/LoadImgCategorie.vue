<template>
  <q-img class="img-size" :src="getPathImg">
    <div class="absolute-bottom text-subtitle1 text-center">
      {{ titre }}
    </div></q-img
  >
</template>
<script setup lang="ts">
import { computed } from 'vue';
import { categoriesType } from 'src/types/evenements';
const data = defineProps<{
  categorieTab: categoriesType;
  titre?: string;
}>();

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
.img-size {
  max-height: 200px !important;
  max-width: 300px !important;
}
</style>
