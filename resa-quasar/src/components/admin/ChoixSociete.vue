<template>
  <v-select :options="listSociete" :readonly="!isSuper" map-otpions v-model="currentSocieteId" label="Societe"
    @change="changeValue"></v-select>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { getSocietes } from 'src/api/configuration';
import { ihmStore } from 'src/stores/ihm';
import { userStore } from 'src/stores/users';


const IhmModule = ihmStore()
const connexionModule = userStore()


type societe = { value: number; label: string };


// récupération des sociétés en base
const listSociete: societe[] = [];
// mémorisation de la société sélectionnée
const currentSocieteId = ref(-1)

const emit = defineEmits(['changesociete'])

defineExpose({
  currentSocieteId,

})

//
// permet de savoir sur quelle société on est positionnée
// pour afficher les utilisateurs associés
function changeValue() {
  emit('changesociete', currentSocieteId.value)
}
//}
//
// on regarde si l'utilisateur est superaDMIN
// car dans ce cas là, il peut voir toutes les sociétés
const isSuper = computed(() => {
  return connexionModule.isSuperUtilisateur
})

//
// on charge les sociétés
onMounted(async () => {
  await initialize();
})

//
// chargement des sociétés venant de la base
async function initialize() {
  // récupération de la société de l'utilisateur connecté
  currentSocieteId.value = connexionModule.getSocieteId

  // récupération des enregistrements existants
  const { data, status, message } = await getSocietes();
  if (status && data) {
    for (var i = 0; i < data.length; i++) {
      for (var element in data) {
        listSociete.push({ value: data[element].id, label: data[element].nom });
      }
    }
  } else {
    IhmModule.displayError({ code: 'ADMIN', param: message });
  }
}

function getSocieteId() {
  return currentSocieteId.value
}
</script>
