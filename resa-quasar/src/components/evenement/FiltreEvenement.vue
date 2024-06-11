<template>
  <q-card class="card-filtre">
    <q-card-section> Vos critères de recherche </q-card-section>
    <q-card-section>
      <div class="row qm-t-xs">
        <div class="col-auto q-mx-xs q-py-xs">
          <q-input
            rounded
            outlined
            clearable
            v-model="filter.search"
            dense
            label="contient..."
            @update:model-value="updatefilter($event, 'search')"
          ></q-input>
        </div>
        <div class="col-lg-3 col-md-3 q-px-xs q-py-xs">
          <q-input rounded outlined dense v-model="filter.date" mask="date">
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date
                    v-model="filter.date"
                    mask="YYYY-MM-DD"
                    @update:model-value="updatefilter($event, 'date')"
                  >
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </div>
        <div class="col-lg-3 col-md-3 q-px-xs q-py-xs">
          <q-select
            rounded
            outlined
            dense
            borderless
            use-chips
            v-model="filter.cat"
            multiple
            :options="listCategories"
            label="Catégories"
            @update:model-value="updatefilter($event, 'cat')"
            style="width: 250px"
          />
        </div></div
    ></q-card-section>
    <q-card-actions align="center">
      <q-btn
        color="primary"
        flat
        rouded
        class="text-center"
        label="rechercher"
        @click="lancerRecherche"
      ></q-btn></q-card-actions
  ></q-card>
</template>

<script setup lang="ts">
import {
  type categoriesType,
  type filterEventType,
} from 'src/types/evenements';

import { ref } from 'vue';
import { setFilterEVt, getFilterEVt } from 'src/utils/cookie';

const emit = defineEmits(['filtreModifie']);
// déclaration des paramètres d'entrée
/*const props = defineProps({
  filter: { type: Object as PropType<filterEventType>, required: true },
});*/

const listCategories = ref<categoriesType>(['randonnée', 'théatre', 'sortie']);

const filter = ref<filterEventType>({ search: '', cat: [], date: '' });
filter.value = getFilterEVt();
// permet de mettre à jour le filtre sur un changement de valeur
function updatefilter(data: filterEventType, key: string) {
  // mise à jour de la data
  //filter.value[key] = data;
  // mise à jour du cookie filtre
  setFilterEVt(filter.value);
}

function lancerRecherche() {
  setFilterEVt(filter.value);
  emit('filtreModifie', filter.value);
}
</script>

<style scoped>
.cardColor {
  opacity: 0.9;
}

.no-uppercase {
  text-transform: none !important;
}

.flexcard {
  display: flex;
  flex-direction: column;
}
.card-filtre {
  background-color: rgba(158, 108, 106, 0.123);
}
</style>
