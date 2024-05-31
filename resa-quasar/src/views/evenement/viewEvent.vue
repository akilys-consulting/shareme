<template>
  <div class="q-pa-md">
    <q-toolbar>
      <q-btn icon="arrow_back_ios" flat @click="$router.go(-1)" />


      <q-tabs v-model="tab" dense class="text-grey" active-color="primary" indicator-color="primary" align="justify"
        narrow-indicator>
        <!--
        <q-tab name="description" icon="description" label="Description" />
        <q-tab name="sortie" icon="groups" label="Sorties" />
        <q-tab name="addSortie" icon="group_add" label="Ajouter Sorties" />
        <q-tab name="modifEvent" icon="edit_note" label="Modifier évènement" />-->
        <q-tab v-for="tab in tabs" :key="tab.name" v-bind="tab" />
      </q-tabs>
    </q-toolbar>
    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="detail">
        <detailEvent />
      </q-tab-panel>

      <q-tab-panel name="sortie">
        <listSortie />
      </q-tab-panel>

      <q-tab-panel name="addSortie">
        <sortieEvent />
      </q-tab-panel>

      <q-tab-panel name="modifEvent">
        <frmEvt />
      </q-tab-panel>
    </q-tab-panels>

  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue'
import { evtStore } from 'src/stores/evenement'
import { EvenementType } from 'src/types/evenements'
import detailEvent from 'src/components/evenement/ConsultEvent.vue'
import listSortie from 'src/components/sortie/ListeSortieEvenement.vue'
import frmEvt from 'src/components/evenement/FormEvent.vue'

//
// récupération de la valeur d'origine
const evtModule = evtStore()


const data = ref<EvenementType>(evtModule.getCurrentEvt)


console.log('data', data.value)

const tab = ref('detail')
const listTabs = [
  { name: 'detail', icon: 'description', label: 'Description' },
  { name: 'sortie', icon: 'groups', label: 'Sorties' },
  { name: 'addSortie', icon: 'group_add', label: 'Ajouter Sorties' },
  { name: 'modifEvent', icon: 'edit_note', label: 'Modifier évènement' },
]
const tabs = ref(listTabs)
</script>
<style></style>
