<template>
  <q-card class="cardEvent" @click="afficherDetail()">
    <q-card-section vertical class="text-body2 bg-blue-grey-1">
      <!--<q-icon class="q-mr-sm" name="today" />{{ evt_data.date_debut }}-->
      <q-icon class="q-mr-sm" name="schedule" />12:00
    </q-card-section>
    <q-card-section horizontal>
      <displayImgCategorie
        :titre="evt_data.titre"
        :categorieTab="evt_data.categories"
      />
      <q-card-section class="q-ml-lg">
        <div class="text-subtitle1">
          <div class="ms-4 text-orange-14 rox">
            {{ convertCategorie }}
          </div>
          <div class="text-body2">Toulouse</div>
        </div>
        <q-card-section>
          <div class="text-h8 col-12">
            {{ evt_data.description }}
          </div></q-card-section
        >
      </q-card-section>
    </q-card-section>
    <q-card-section class="text-body2">
      <div class="text-right">
        <q-btn
          outline
          size="sm"
          class="q-mx-xs"
          round
          v-if="addActivites"
          color="primary"
          icon="group_add"
        />
        <q-btn
          outline
          size="sm"
          class="q-mx-xs"
          round
          color="primary"
          icon="share"
        />
        <q-btn
          outline
          size="sm"
          class="q-mx-xs"
          round
          color="primary"
          icon="euro"
        />
        <q-btn
          outline
          size="sm"
          class="q-mx-xs"
          round
          color="primary"
          icon="people"
        >
          <q-badge rounded transparent color="red" floating>4</q-badge>
        </q-btn>
      </div>
    </q-card-section>
  </q-card>
  <!--
  <q-card @click="ConsultEvt">
    <q-card-section horizontal>
      <displayImgCategorie
        :titre="evt_data.titre"
        :categorie="evt_data.categories"
      />
      <q-card-section>
        <q-card-section>
          <div class="text-h8 col-12">{{ evt_data.description }}</div>
        </q-card-section>
        <q-card-section>
          <div class="text-h8 col-12">Organisateur : Mairie de Toulouse</div>
        </q-card-section>

        <q-separator />
        <q-card-section>
          <div class="text-h8">Catégorie : balade, chateau</div>
        </q-card-section>
        <q-separator />

        <q-card-actions class="col-12 q-mt-sm" align="right">
          <q-btn flat round color="red" icon="favorite" />
          <q-btn round flat color="primary" icon="edit_calendar" />
          <q-btn flat round color="blue" icon="share" />
        </q-card-actions>
      </q-card-section>
    </q-card-section>
  </q-card>-->
</template>
<script setup lang="ts">
import { computed } from 'vue';
import { type EvenementType } from 'src/types/evenements';
import displayImgCategorie from 'src/components/ihm/LoadImgCategorie.vue';
import { useRouter } from 'vue-router';

import { setCurrentEvt } from 'src/utils/cookie';

import { userStore } from 'src/stores/users';
const userModule = userStore();

const data = defineProps<{ evt_data: EvenementType }>();

const router = useRouter();

const convertCategorie = computed(() => {
  return data.evt_data.categories.toString();
});

const addActivites = computed(() => {
  return userModule.getIsConnected;
});
//
// fonction d'affichage de l'évènement sélectionné
function afficherDetail() {
  // on appel la page d'affichage de l'évènement
  setCurrentEvt(data.evt_data);
  console.log('data', data.evt_data);
  router.push({
    name: 'detailEvenement',
  });
}
</script>

<style scoped>
.q-card__section--vert {
  width: 100% !important;
  padding: 4px;
}
.q-card__actions {
  padding: 0px !important;
}
</style>
