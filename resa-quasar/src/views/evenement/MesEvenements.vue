<template>
  <q-expansion-item
    icon="search"
    color="primary"
    class="lt-md"
    dense
    default-opened
    ><div class="card-filtre-expand">
      <filtrageEvenement
        @filtreModifie="refraichirListeEVenement"
        class="card-filtre-expand"
      ></filtrageEvenement></div
  ></q-expansion-item>
  <div class="gt-sm">
    <filtrageEvenement
      @filtreModifie="refraichirListeEVenement"
      class="card-filtre"
    ></filtrageEvenement>
  </div>
  <q-card class="my-card"> </q-card>
  <div class="q-pa-md">
    <q-table
      style="height: 400px"
      flat
      bordered
      :title="no_data ? 'Pas de données à afficher' : 'Les activités à venir'"
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
          <syntheseEvt :evt_data="props.row" />
        </div>
      </template>
    </q-table>
  </div>
  >
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import syntheseEvt from 'src/components/evenement/SyntheseEvenement.vue';
import filtrageEvenement from 'src/components/evenement/FiltreEvenement.vue';

import { listEvenements } from 'src/api/evenement';
import { type EvenementType } from 'src/types/evenements';

import { ihmStore } from 'src/stores/ihm';

const ihmModule = ihmStore();
const TabEvenements = ref<EvenementType[]>([]);
const pagination = ref({
  rowsPerPage: 0,
});
const no_data = ref(false);

onMounted(async () => {
  await getAllevt();
  console.log('data evet', TabEvenements.value);
});

// récupération des salons
//
async function getAllevt() {
  try {
    ihmModule.startWaiting();
    no_data.value = false;
    const { data, status, message } = await listEvenements();
    ihmModule.stopWaiting();
    if (status) {
      //nbRow.value = count ? count : 0;
      TabEvenements.value = <EvenementType[]>data;
      if (TabEvenements.value.length == 0) {
        no_data.value = true;
      }
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

async function refraichirListeEVenement() {
  await getAllevt();
}
</script>
<style>
.card-filtre {
  max-width: 80%;
}
.card-filtre-expand {
  max-width: 100%;
}
</style>
