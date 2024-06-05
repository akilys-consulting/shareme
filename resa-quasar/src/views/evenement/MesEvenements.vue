<template>
  <!--
  <q-page padding>
    <h1>Main Page</h1>
    <q-table grid ref="tableRef" hide-pagination :rows="listSalon" row-key="nom" v-model:pagination="pagination"
      :loading="loading" @request="onRequest">

      <template v-slot:item="props">
        <evtDetail :evt_item="props.row" />
      </template>

      <template v-slot:pagination="scope">

        <q-btn v-if="pagination.page > 1" icon="first_page" round dense flat :disable="scope.isFirstPage"
          @click="scope.firstPage" />

        <q-btn icon="chevron_left" round dense :color="scope.isFirstPage ? 'red' : 'green'" :disable="scope.isFirstPage"
          @click="scope.prevPage" />
        <div class="q-px-sm text-subtitle text-grey-1">
          {{ pagination.page }} / {{ scope.pagesNumber }}
        </div>

        <q-btn icon="chevron_right" round dense :color="scope.isLastPage ? 'red' : 'green'" :disable="scope.isLastPage"
          @click="scope.nextPage" />

        <q-btn v-if="scope.pagesNumber > 2" color="grey-8" round dense flat :disable="scope.isLastPage"
          @click="scope.lastPage" />

      </template>
      <template v-slot:loading>
        <q-inner-loading showing color="primary" />
      </template>
    </q-table>
    <div class="row justify-center q-mt-md">
      <q-pagination v-model="pagination.page" boundary-numbers @click="onRequest({ pagination: pagination })"
        :max="pagesNumber" size="sm" />
    </div>
  </q-page>-->
  <h1>Mes evènements </h1>
</template>


<script setup lang="ts">

import { onMounted, ref, computed} from 'vue'
import { Quasar } from 'quasar'

import { listEvenements } from 'src/api/evenement';
import { type EvenementType,  } from 'src/types/evenements';

import { ihmStore } from 'src/stores/ihm'
//const ihmModule = ihmStore()
//const { displayFilter, dataFilter } = storeToRefs(ihmModule)



//
//import evtDetail from 'src/components/evenement/EvenementDetail.vue'
//import menucriteria from 'src/components/evenement/FiltreEvenementCriteria.vue'

let TabEvenements = ref<EvenementType[]>([]);

//let filtering = reactive<filterEventType>(ihmModule.getDataFilter)


//watch(filtering, () => {
//  onRequest({ pagination: pagination.value })
//})

//watch(dataFilter, () => {
//  onRequest({ pagination: pagination.value })
//})


onMounted(async () => {
  const TabEvenements = getAllevt()
  console.log('data evet', TabEvenements)

})




// récupération des salons
//
async function getAllevt() {

 
  const { data, status, message } = await listEvenements();
  if (status) {
    //nbRow.value = count ? count : 0;
    console.log('data', data);
  } else {
    console.log('message', status, message);
  }
  //pagination.value.rowsNumber = count ? count : 0
  return data ? data : [];
}


</script>

<style>
.screenwide {
  max-width: 100% !important;
}

.screenwide .q-table {
  max-width: 100% !important;
}

.screenwide td {
  white-space: normal !important;
  word-wrap: normal !important;
  hyphens: manual;
}

.screenwide th {
  text-align: center !important;
}
</style>
