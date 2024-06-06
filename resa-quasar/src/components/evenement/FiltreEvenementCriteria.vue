<template>
  <q-btn color="primary" class="q-mb-lg text-center" label="rechercher"></q-btn>
  <q-input rounded hint="appuyer sur entrée" @clear="clearFilter('search')" outlined clearable
     v-model="filter.search" dense label="contient..."
    maxlength="30"></q-input>
  <q-date @update:model-value="updatefilter($event, 'date')" v-model="filter.date" mask="YYYY-MM-DD">
  </q-date>


  <q-select class="q-ma-sm" @update:model-value="updatefilter($event, 'cat')" borderless use-chips v-model="filter.cat"
    multiple :options="listCategories" label="Catégories" style="width: 250px" />


  <!--</q-popup-proxy>
</q-btn>-->
</template>


<script setup lang="ts">


import { getCategories } from 'src/api/evenement';
import { type categoriesType, type filterEventType } from 'src/types/evenements';
import { setFilterEVt } from 'src/utils/cookie'
import { onMounted, ref, computed, type PropType } from 'vue'



const emit = defineEmits(['update:modelValue'])
// déclaration des paramètres d'entrée
const props = defineProps({
  filter: { type: Object as PropType<filterEventType>, required: true },
})


const listCategories = ref<categoriesType>([])
const showCategorie = ref(false)

const filter = computed({  // Use computed to wrap the object
  get: () => props.filter,
  set: (value: filterEventType): any => {
    console.log('change')

    // mémorisation de la recherche dans le coockie
    setFilterEVt(value)
    // envoi au parent de la data à jour
    emit('update:modelValue', value)
  }
});

function lancerRecherche() {
  setFilterEVt(filter.value)
  emit('update:modelValue', filter.value)
}
onMounted(async () => {
  chargementCategorie()
})


//
// permet de charger la liste des catégories
async function chargementCategorie() {
  const result = await getCategories();
  if (result) {
    if (typeof result.data != 'undefined') {
      listCategories.value = Object.values(result.data);
      console.log('categorie', listCategories.value)
      showCategorie.value = Object.keys(listCategories).length > 0;
    }
  }
}

//
// permet de mettre à jour le filtre sur un changement de valeur
function updatefilter(data: filterEventType, key: string) {
  // mise à jour de la data
  //filter.value[key] = data
  // mise à jour du cookie filtre
  setFilterEVt(filter.value)
}

function clearFilter(key: string) {
  //filter.value[key] = null
  // mise à jour du cookie filtre
  setFilterEVt(filter.value)
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
