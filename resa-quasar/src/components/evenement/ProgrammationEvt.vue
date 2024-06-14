<template>
  <q-toggle
    v-model="activeProgrammation"
    label="Evènement récurrent"
    left-label
  />

  <q-table
    hide-pagination
    style="min-width: 600px"
    flat
    bordered
    title="Programmations"
    :rows="programmation"
    :columns="columns"
    row-key="name"
    binary-state-sort
    v-if="activeProgrammation"
  >
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td key="type" :props="props">
          <q-select :options="listtypeProgrammation" v-model="props.row.type">
          </q-select>
        </q-td>
        <q-td key="datedebut" :props="props">
          <q-input v-model="props.row.datedebut">
            <template v-slot:prepend>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date v-model="props.row.datedebut" mask="DD/MM/YYYY HH:mm">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>

            <template v-slot:append>
              <q-icon name="access_time" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-time
                    v-model="props.row.datedebut"
                    mask="DD/MM/YYYY HH:mm"
                    format24h
                  >
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-time>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </q-td>
        <q-td key="datefin" :props="props">
          <q-input v-model="props.row.dateFin">
            <template v-slot:prepend>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date v-model="props.row.dateFin" mask="DD/MM/YYYY HH:mm">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>

            <template v-slot:append>
              <q-icon name="access_time" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-time
                    v-model="props.row.dateFin"
                    mask="DD/MM/YYYY HH:mm"
                    format24h
                  >
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-time>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </q-td>
      </q-tr>
    </template>
  </q-table>
  <q-card flat class="q-pa-md" style="max-width: 300px" v-else>
    <q-input v-model="programmation[0].datedebut">
      <template v-slot:prepend>
        <q-icon name="event" class="cursor-pointer">
          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
            <q-date
              v-model="programmation[0].datedebut"
              mask="DD/MM/YYYY HH:mm"
            >
              <div class="row items-center justify-end">
                <q-btn v-close-popup label="Close" color="primary" flat />
              </div>
            </q-date>
          </q-popup-proxy>
        </q-icon>
      </template>

      <template v-slot:append>
        <q-icon name="access_time" class="cursor-pointer">
          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
            <q-time
              v-model="programmation[0].datedebut"
              mask="DD/MM/YYYY HH:mm"
              format24h
            >
              <div class="row items-center justify-end">
                <q-btn v-close-popup label="Close" color="primary" flat />
              </div>
            </q-time>
          </q-popup-proxy>
        </q-icon>
      </template>
    </q-input>
  </q-card>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import {
  type ProgrammationType,
  K_typeProgrammation,
} from 'src/types/programmation_evt';

// déclaration des paramètres d'entrée
const props = defineProps<{
  progEvt: ProgrammationType[];
}>();

const columns = [
  { name: 'type', label: 'type progammation', field: 'type', align: 'left' },
  { name: 'datedebut', label: 'date debut', field: 'datedebut', align: 'left' },
  { name: 'datefin', label: 'date fin', field: 'datefin', align: 'left' },
];

//const typeProgrammation = K_typeProgrammation;
const activeProgrammation = ref(false);
const programmation = ref(props.progEvt);

const listtypeProgrammation = ref(K_typeProgrammation);
onMounted(() => {
  // on identifie si il y a une prgrammation
  if (typeof props.progEvt[0] != 'undefined') {
    activeProgrammation.value =
      props.progEvt.length == 0 && props.progEvt[0].type == 'Days';
  }
});
</script>

<style scoped>
.q-date__view {
  min-height: 257px !important;
}
</style>
