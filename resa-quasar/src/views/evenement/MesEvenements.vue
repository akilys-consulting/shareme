<template>
  <div class="q-pa-md">
    <q-table
      style="height: 400px"
      flat
      bordered
      title="Les activités à venir"
      grid
      :rows="TabEvenements"
      row-key="index"
      virtual-scroll
      v-model:pagination="pagination"
      :rows-per-page-options="[0]"
      hide-bottom
      hide-header
    >
      <template v-slot:item="props">
        <div class="q-pa-xs q-pb-md col-xs-12 col-sm-12 col-md-12">
          <evtDetail :evt_data="props.row" />
        </div>
      </template>
    </q-table>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import evtDetail from 'src/components/evenement/ConsultEvent.vue';

import { listEvenements } from 'src/api/evenement';
import { type EvenementType } from 'src/types/evenements';

import { ihmStore } from 'src/stores/ihm';

const ihmModule = ihmStore();
const TabEvenements = ref<EvenementType[]>([]);
const pagination = ref({
  rowsPerPage: 0,
});

onMounted(async () => {
  await getAllevt();
  console.log('data evet', TabEvenements.value);
});

// récupération des salons
//
async function getAllevt() {
  try {
    ihmModule.startWaiting();
    const { data, status, message } = await listEvenements();
    ihmModule.stopWaiting();
    if (status) {
      //nbRow.value = count ? count : 0;
      TabEvenements.value = <EvenementType[]>data;
    } else {
      ihmModule.displayError({ code: 'EVGA', param: message });
      console.log('message', status, message);
    }
    //pagination.value.rowsNumber = count ? count : 0
    return data ? data : [];
  } catch (error) {
    ihmModule.stopWaiting();
    let message = '';
    if (error instanceof Error) message = error.message;
    else message = String(error);
    //
    // affichage d'un message d'erreur sur retour 0 enreg
    ihmModule.displayError({ code: 'EVGA', param: message });
  }
}
</script>
