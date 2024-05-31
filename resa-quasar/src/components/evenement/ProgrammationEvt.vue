<template>
  <q-table hide-pagination style="min-width: 600px" flat bordered title="Programmations" :rows="programmation"
    :columns="columns" row-key="name" binary-state-sort>
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td key="type" :props="props">
          <q-select :options="typeProgrammation" @update:model-value="props.row.type" v-model="props.row.type"
            option-value="value" option-label="text">
          </q-select>
        </q-td>
        <q-td key="datedebut" :props="props">
          {{ props.row.datedebut }}
          <q-popup-edit v-model="props.row.datedebut" title="Update datedebut" @save="alert" buttons v-slot="scope">
            <div class="row">
              <div class="col-6">
                <q-date landscape v-model="scope.value" mask="YYYY-MM-DD HH:mm" />
              </div>
              <div class="col-6">
                <q-time v-model="scope.value" landscape mask="YYYY-MM-DD HH:mm" />
              </div>
            </div>
          </q-popup-edit>
        </q-td>
        <q-td key="datefin" :props="props">
          {{ props.row.datedebut }}
          <q-popup-edit v-model="props.row.datefin" title="Update datefin" @save="alert" buttons v-slot="scope">
            <div class="row">
              <div class="col-6">
                <q-date landscape v-model="scope.value" mask="YYYY-MM-DD HH:mm" />
              </div>
              <div class="col-6">
                <q-time v-model="scope.value" landscape mask="YYYY-MM-DD HH:mm" />
              </div>
            </div>
          </q-popup-edit>
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script setup lang="ts">

import { ref, onMounted } from 'vue'
import { K_typeProgrammation, type ProgrammationType } from 'src/types/evenements';



const columns = [
  { name: 'type', label: 'type progammation', field: 'type' },
  { name: 'datedebut', label: 'date debut', field: 'datedebut' },
  { name: 'datefin', label: 'date fin', field: 'datefin' }]


//
// stokage de la programmation lue de la base de donnéesM@toine2503Enac

const programmation = ref<ProgrammationType[]>([]);
const typeProgrammation = K_typeProgrammation

let updateData = ref(false)



onMounted(() => {
  programmation.value = [{ id: 12, type: 'days', datedebut: '12/07/2023', datefin: '24/07/2023', jours: [], evenementid: 12 }]

})

function alert(value: any, intialValue: any) {
  updateData.value = value != intialValue || updateData.value
  console.log('value', value, intialValue)
}
</script>

<style></style>
