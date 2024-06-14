<template>
  <div class="row">
    <div class="col col-6 q-pr-xl">
      <q-uploader
        color="secondary"
        v-model="selectedImage"
        label="Nouvelle image (max 2k)"
        class="q-pt-md"
        @rejected="onRejected"
        :max-file-size="K_size_avatar"
        accept=".jpeg,.jpg,.png,.gif"
        :url="getUrl"
        @added="ajoutFichier"
        style="max-width: 300px"
        @removed="deleteFile"
      />
    </div>
    <div class="text-center q-pl-xl col col-6">
      <q-img class="col-6 img-size" :src="modelValue"> </q-img>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { ihmStore } from 'src/stores/ihm';
import { K_imgDefaut, K_size_avatar } from 'src/utils/config';

const ihmModule = ihmStore();
const props = defineProps(['modelValue', 'path']);
//
// récupération de la valeur d'origine
//
// permet de mettre à jour la props
const modelValue = computed({
  // Use computed to wrap the object
  get: () => {
    if (!props.modelValue) return props.path + K_imgDefaut;
    return props.modelValue;
  },
  set: (value) => {
    emit('update:modelValue', value);
  },
});

//
// gestion d'un event pour synchroniser avec la valeur d'appel
const emit = defineEmits(['changeFile']);

const selectedImage = ref();

//
// suppression de l'image à télécharger
function deleteFile() {
  selectedImage.value = null;
}
//
// fonction de récupération du chemin du fichier
async function getUrl(file) {
  selectedImage.value = file;
  return '';
}

function ajoutFichier(files) {
  emit('changeFile', files);
}

function onRejected() {
  // Notify plugin needs to be installed
  // https://quasar.dev/quasar-plugins/notify#Installation
  ihmModule.displayError({ code: 'FUEX', param: K_size_avatar });
}
</script>
