<template>
  <q-img class="img-size col-6" :src="getPathImg">
    <div class="absolute-bottom text-subtitle1 text-center">
      {{ titre }}
    </div></q-img
  >
</template>
<script setup lang="ts">
import { computed } from 'vue';
const data = defineProps({ categorie: [], titre: String });

function strNoAccent(chaine: string) {
  return chaine.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

// a computed ref
const getPathImg = computed(() => {
  const cat = JSON.parse(data.categorie);
  return 'categories/' + strNoAccent(cat[0]).toLowerCase() + '.jpg';
});
</script>
<style scoped>
.img-size {
  max-height: 200px !important;
  max-width: 300px !important;
}
</style>
