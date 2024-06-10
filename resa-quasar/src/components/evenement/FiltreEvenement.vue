<template>
  <q-card>
    <q-card-section>
      <q-input
        rounded
        hint="appuyer sur entrée"
        @clear="clearFilter('search')"
        outlined
        clearable
        v-model="filter.search"
        dense
        label="contient..."
        maxlength="30"
      ></q-input>
      <q-date
        @update:model-value="updatefilter($event, 'date')"
        v-model="filter.date"
        mask="YYYY-MM-DD"
      >
      </q-date>

      <q-select
        class="q-ma-sm"
        @update:model-value="updatefilter($event, 'cat')"
        borderless
        use-chips
        v-model="filter.cat"
        multiple
        :options="listCategories"
        label="Catégories"
        style="width: 250px"
      />
    </q-card-section>
    <q-card-actions>
      <q-btn
        color="primary"
        class="q-mb-lg text-center"
        label="rechercher"
      ></q-btn>
    </q-card-actions>
  </q-card>
</template>

<script setup lang="ts">

import {
  type categoriesType,
  type filterEventType,
} from 'src/types/evenements';

import { onMounted, ref, computed, type PropType } from 'vue';
import { setFilterEVt } from 'src/utils/cookie';

const emit = defineEmits(['update:modelValue']);
// déclaration des paramètres d'entrée
/*const props = defineProps({
  filter: { type: Object as PropType<filterEventType>, required: true },
});*/

const listCategories = ref<categoriesType>([]);
const showCategorie = ref(false);

const filter = ref<filterEventType>()
/*const filter = computed({
  // Use computed to wrap the object
  get: () => props.filter,
  set: (value: filterEventType): any => {
    console.log('change');

    // mémorisation de la recherche dans le coockie
    setFilterEVt(value);
    // envoi au parent de la data à jour
    emit('update:modelValue', value);
  },
});*/

//
// permet de mettre à jour le filtre sur un changement de valeur
function updatefilter(data: filterEventType, key: string) {
  // mise à jour de la data
  //filter.value[key] = data
  // mise à jour du cookie filtre
  setFilterEVt(filter.value);
}

function clearFilter(key: string) {
  //filter.value[key] = null
  // mise à jour du cookie filtre
  setFilterEVt(filter.value);
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
</style>
