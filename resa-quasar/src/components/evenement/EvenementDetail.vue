<template>
  <div class="q-px-xs q-py-sm col-xs-12 col-sm-12 col-md-6 col-lg-3 col-lg-2">
    <q-card class="cardEvent" @click="afficherDetail()">
      <q-card-section class="text-body2 bg-blue-grey-1">
        <q-icon class="q-mr-sm" name="today" />{{ getDate }}
        <q-icon class="q-mr-sm" name="schedule" />{{ getTime }}
      </q-card-section>
      <displayImgCategorie
        :titre="evt_data.titre"
        :categorie="evt_data.categories"
      />

        <q-card-section>
          <div class="text-subtitle1">
            <div class="ms-4 text-orange-14 rox">
              {{ getCategories }}
            </div>
            <div class="text-body2">
              {{ getVille }}
            </div>
          </div>
          <q-card-section class="text-body2">
            <div class="text-right">
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
        </q-card-section>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { date } from 'quasar';
import { type EvenementType } from 'src/types/evenements';
import displayImgCategorie from 'src/components/ihm/LoadImgCategorie.vue';

// déclaration des paramètres d'entrée
const props = defineProps<{
  evt_item: EvenementType;
}>();

const router = useRouter();

// déclaration des variables
//
const planning = ref<typePlanning[]>([
  { start: '', end: '', permanentDays: [] },
]);

// actions faites au chargement du composant
onMounted(async () => {
  await decodePlanning();
});

const getimageEvenement = computed(() => {
  if (!props.evt_item.image) {
    return K_storageImageEvent + K_imgDefaut;
  } else {
    return props.evt_item.image;
  }
});

const getDate = computed(() => {
  if (planning.value.length > 1) {
    return date.formatDate(planning.value[1].start, 'dddd D MMMM YYYY');
  } else {
    return '... calcul...';
  }
});

const getTime = computed(() => {
  if (planning.value.length > 1) {
    return date.formatDate(planning.value[1].start, 'HH[:]mm');
  } else {
    return '... calcul...';
  }
});

const getCategories = computed(() => {
  if (props.evt_item.categories) {
    return props.evt_item.categories.toString();
  } else {
    return '';
  }
});

const getVille = computed(() => {
  if (
    props.evt_item.adresse.value &&
    props.evt_item.adresse.value.adr.length > 0
  ) {
    return props.evt_item.adresse.value.adr;
  } else return 'Pas de lieu défini';
});

async function decodePlanning() {
  // on récupère la programmation
  console.log('result', props.evt_item.id);
  const result = await getProgrammation({ evenementId: props.evt_item.id });
  console.log('result', result);
  if (result.status) {
    const programmation = result.data;
    if (programmation && programmation.length > 0) {
      programmation.forEach((prog: any) => {
        console.log('prog', prog);
        var currentDate = date.extractDate(prog.datedebut, K_formatDateBase);
        let finDate = date.addToDate(
          date.extractDate(prog.datefin, K_formatDateBase),
          { hours: 2 }
        );

        if (prog.datefin)
          finDate = date.extractDate(prog.datefin, K_formatDateBase);

        let guard = 0;
        // gestion perticulière sur une porgrammation permanente
        if (prog.type) {
          if (prog.type === '*') {
            // évènement permanent
            planning.value.push({
              start: date.formatDate(currentDate, 'YYYY-MM-DD HH:mm'),
              end: date.formatDate(finDate, 'YYYY-MM-DD HH:mm'),
              permanentDays: prog.jours,
            });
            guard = 5000;
          } else {
            while (
              date.getDateDiff(currentDate, finDate) <= 0 &&
              guard++ <= 4000
            ) {
              // creation de l'évènement
              planning.value.push({
                start: date.formatDate(currentDate, 'YYYY-MM-DD HH:mm'),
                end: date.formatDate(currentDate, 'YYYY-MM-DD HH:mm'),
              });
              //
              // le type weeks n'existe pas dans la fonction date.addToDate
              // on transforme le type weeks en days
              if (prog.type === 'weeks') {
                currentDate = date.addToDate(currentDate, { days: 7 });
              } else {
                currentDate = date.addToDate(currentDate, { [prog.type]: 1 });
              }
            }
          }
        }
      });
    }
  }
}
//
// fonction d'affichage de l'évènement sélectionné
function afficherDetail() {
  // on charge l'évènement à afficher
  evtModule.setCurrentEvt(props.evt_item);
  // on appel la page d'affichage de l'évènement
  router.push({
    name: 'detailevt',
  });
}
</script>

<style scoped>
.my-card {
  width: 100%;
  max-width: 220px;
}

.cardEvent {
  width: 100%;
  min-height: 150px;
  max-height: 200px;
}

.img-size {
  max-height: 100px;
  min-height: 100px;
}

.card-image {
  background: rgba(0, 0, 0, 0.3);
}
</style>
