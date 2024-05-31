<template>
  <q-page padding>
    <h1>Main Page</h1>
<!--    <q-table grid ref="tableRef" hide-pagination :rows="listSalon" row-key="nom" v-model:pagination="pagination"
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
    </div>-->
  </q-page>
</template>


<script setup lang="ts">

//import { onMounted, ref, computed} from 'vue'
//import { Quasar } from 'quasar'

//import { userStore } from 'src/stores/users';
//const connexionModule = userStore()

//import { ihmStore } from 'src/stores/ihm'
//const ihmModule = ihmStore()
//const { displayFilter, dataFilter } = storeToRefs(ihmModule)


//import { listEvenements } from 'src/api/evenement';
//import { type EvenementType, type filterEventType } from 'src/types/evenements';
//
//import evtDetail from 'src/components/evenement/EvenementDetail.vue'
//import menucriteria from 'src/components/evenement/FiltreEvenementCriteria.vue'

//let listSalon = ref<EvenementType[]>([]);

//let filtering = reactive<filterEventType>(ihmModule.getDataFilter)


//watch(filtering, () => {
//  onRequest({ pagination: pagination.value })
//})

//watch(dataFilter, () => {
//  onRequest({ pagination: pagination.value })
//})

/*
onMounted(async () => {
  tableRef.value.requestServerInteraction()
  console.log('langage', Quasar.lang.getLocale())

})
*/

/*
const nbRow = ref(0)
const pagination = ref({
  sortBy: 'desc',
  descending: false,
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10
})

const pagesNumber = computed(() => {
  const data = Math.ceil(pagination.value.rowsNumber / pagination.value.rowsPerPage)
  return Math.ceil(pagination.value.rowsNumber / pagination.value.rowsPerPage)
})


// récupération des salons
//
async function getAllsalon(startRow: number, countRow: number, filter: filterEventType) {

  let statusEvt = null;
  //
  // on recupère l'utilisateur connecté
  // un organisateur est requis sur le profil n'est pas client ou design
  // le prodil design a accès à tous les évènements
  if (connexionModule.userIsDesign || connexionModule.userIsCommercial) {
    filter.societeId = connexionModule.getSocieteId;
  }
  if (connexionModule.userIsClient) {
    statusEvt = 'C';
  }

  const { data, count, status, message } = await listEvenements({
    offset: startRow,
    limit: countRow,
    filter: filter,
    status: statusEvt,
  });
  if (status) {
    nbRow.value = count ? count : 0;
    console.log('data', data);
  } else {
    console.log('message', status, message);
  }
  pagination.value.rowsNumber = count ? count : 0
  return data ? data : [];
}


async function onRequest(props: any) {
  const { page, rowsPerPage, sortBy, descending } = props.pagination

  loading.value = true

  // emulate server

  // update rowsCount with appropriate value
  //pagination.value.rowsNumber = getRowsNumberCount(filter)

  // get all rows if "All" (0) is selected
  const fetchCount = rowsPerPage === 0 ? pagination.value.rowsNumber : rowsPerPage

  // calculate starting row of data
  const startRow = (page - 1) * rowsPerPage

  // fetch data from "server"
  const returnedData = await getAllsalon(startRow, fetchCount, filtering)

  // clear out existing data and add new
  listSalon.value = returnedData

  // don't forget to update local pagination object
  pagination.value.page = page
  pagination.value.rowsPerPage = rowsPerPage
  pagination.value.sortBy = sortBy
  pagination.value.descending = descending

  // ...and turn of loading indicator
  loading.value = false

}
*/

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
