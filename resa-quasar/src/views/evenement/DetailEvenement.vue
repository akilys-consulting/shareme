<template>
  <div class="q-pa-md">
    <q-toolbar>
      <q-btn icon="arrow_back_ios" flat @click="$router.go(-1)" />

      <q-tabs
        v-model="tab"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
      >
        <q-tab v-for="tab in tabs" :key="tab.name" v-bind="tab" />
      </q-tabs>
    </q-toolbar>
    <q-separator />

    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="detail">
        <q-card>
          <q-card-section horizontal>
            <displayImgCategorie
              :titre="currentEvt.titre"
              :categorie="currentEvt.categories"
            />
            <q-card-section>
              <q-card-section>
                <div class="text-h3 col-12">{{ currentEvt.titre }}</div>
                <br />

                <div class="text-h6 col-12">
                  Organisateur : Mairie de Toulouse
                </div>
              </q-card-section>

              <q-separator />
              <q-card-section>
                <div class="text-h6">Catégorie : balade, chateau</div>
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
      </q-tab-panel>

      <q-tab-panel name="sortie"> </q-tab-panel>

      <q-tab-panel name="addSortie"> </q-tab-panel>

      <q-tab-panel name="modifEvent"> </q-tab-panel>
    </q-tab-panels>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { evtStore } from 'src/stores/evenement';
import displayImgCategorie from 'src/components/ihm/LoadImgCategorie.vue';

//const data = defineProps<{ test: string }>();
const evtModule = evtStore();
const currentEvt = ref(evtModule.getCurrentEvt);

const tab = ref('detail');
const listTabs = [
  { name: 'detail', icon: 'description', label: 'Description' },
  { name: 'sortie', icon: 'groups', label: 'Sorties' },
  { name: 'addSortie', icon: 'group_add', label: 'Ajouter Sorties' },
  { name: 'modifEvent', icon: 'edit_note', label: 'Modifier évènement' },
];
const tabs = ref(listTabs);
</script>
<style></style>
