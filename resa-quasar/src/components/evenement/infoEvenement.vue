<template>
  <q-card flat>
    <q-card-section horizontal>
      <q-card-section class="col-5 flex flex-center">
        <displayImgCategorie
          :titre="currentEvt.titre"
          :categorieTab="currentEvt.categories"
      /></q-card-section>
      <q-card-section>
        <q-card-section>
          <div class="text-h4 col-12">{{ currentEvt.titre }}</div>
        </q-card-section>
        <q-separator />
        <q-card-section>
          <div class="text-subtitle1 col-12">
            Organisateur : {{ currentEvt.auteur.name }}
          </div>
        </q-card-section>

        <q-separator />
        <q-card-section>
          <div class="text-subtitle1 text-orange-14">
            Catégories : {{ convertCategorie }}
          </div>
        </q-card-section>

        <q-separator />
        <q-card-section>
          <div class="text-body1">
            {{ currentEvt.description }}
          </div>
        </q-card-section>
      </q-card-section>
    </q-card-section>
    <div class="q-ma-sm" id="map"></div>
  </q-card>
</template>
<script setup lang="ts">
import { ref, onBeforeMount, computed } from 'vue';

import { EvenementType, evenementVide } from 'src/types/evenements';

import { useRouter } from 'vue-router';
const router = useRouter();

import { getCurrentEvt } from 'src/utils/cookie';

import displayImgCategorie from 'src/components/ihm/LoadImgCategorie.vue';

const currentEvt = ref<EvenementType>(evenementVide);

onBeforeMount(() => {
  currentEvt.value = getCurrentEvt();
  if (typeof currentEvt.value == 'undefined') {
    currentEvt.value = evenementVide;
    router.push({ name: 'accueil' });
  }
});

const convertCategorie = computed(() => {
  return currentEvt.value.categories.toString();
});
</script>
