<template>
  <q-select class="q-pt-md adresse" :label="$t('texte.FPAD')" option-value="value" option-label="data" map-options
    :model-value="modelValue" use-input hide-selected fill-input input-debounce="0"
    @update:model-value="val => OnChange(val)" :options="items" @filter="filterFn" @input-value="setModel">
    <template v-slot:no-option>
      <q-item>
        <q-item-section class="text-grey">
          No results
        </q-item-section>
      </q-item>
    </template>
  </q-select>
</template>

<script setup lang="ts">

import { ref, computed } from 'vue'
import { api } from 'boot/axios'


//
// récupération de la valeur d'origine
const props = defineProps(['modelValue'])
//
// gestion d'un event pour synchroniser avec la valeur d'appel
const emit = defineEmits(['update:modelValue'])

//
// permet de mettre à jour la props
const modelValue = computed({  // Use computed to wrap the object
  get: () =>
    props.modelValue ? props.modelValue.adr : null,
  set: (value) => {
    emit('update:modelValue', value)
  }
});

// champ contenant la liste des propositions d'adresse
const items = ref<any[]>([])
//adresseChoisie: adresseListObject = { data: "", value: defaultAdresse };

//
// permet de mettre à jour la liste en fonction de la saisie
async function filterFn(val: string, update: any, abort: any) {
  update(async () => {
    lireAdresse(val)
  })
}

async function lireAdresse(adrSaisie: any) {
  try {
    if (adrSaisie.length > 4) {
      let chaineEncode = encodeURI(adrSaisie);
      let request = await api.get(
        'https://api-adresse.data.gouv.fr/search/?q=' +
        chaineEncode +
        '&type=housenumber&autocomplete=1',
      );
      const data = await request.data;
      items.value = [];
      if (data.features.length == 0) {
        //alert("test");
        items.value.push({
          data: adrSaisie,
          value: {
            adr: adrSaisie,
            latLng: {
              lat: null,
              lng: null,
            },
          },
        });
      }
      data.features.forEach((row: any) => {
        items.value.push({
          data: row.properties.label,
          value: {
            adr: row.properties.city,
            latLng: {
              lat: row.geometry.coordinates[1],
              lng: row.geometry.coordinates[0],
            },
          },
        });
      });
    }
  } catch (error: any) {
    console.log('error', error);
  }
}

function setModel(val: any) {
  //modelValue.value = val
}

function OnChange(nvlAdresse: any) {
  modelValue.value = nvlAdresse.value
}

</script>
<style>
.ap-input {
  border: none;
}

.adresse {
  min-width: 80%
}
</style>
