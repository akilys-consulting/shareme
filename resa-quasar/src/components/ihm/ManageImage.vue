<template>
  <div class="row" v-if="acceptUpload">
    <q-uploader
      flat
      v-model="selectedImage"
      label="Nouvelle image (max 2k)"
      class="col-12"
      @rejected="onRejected"
      :max-file-size="K_size_avatar"
      accept=".jpeg,.jpg,.png,.gif"
      :url="getUrl"
      @added="ajoutFichier"
      style="max-width: 300px"
      @removed="deleteFile"
    />
    <q-img class="img-size col q-ml-xl col-12" :src="modelValue"> </q-img>
  </div>

  <q-img class="img-size-only" v-else :src="modelValue">
    <div class="absolute-bottom text-subtitle1 text-center">
      {{ titre }}
    </div></q-img
  >
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { ihmStore } from 'src/stores/ihm';
import { K_size_avatar } from 'src/utils/config';

const ihmModule = ihmStore();
const props = defineProps({
  titre: { type: String },
  modelValue: { type: String, required: true },
  acceptUpload: { type: Boolean },
});

const modelValue = computed({
  // Use computed to wrap the object
  get: () => {
    return 'http://localhost:8100/api/photo/' + props.modelValue;
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

function scaleImage(
  srcwidth,
  srcheight,
  targetwidth,
  targetheight,
  fLetterBox
) {
  var result = { width: 0, height: 0, fScaleToTargetWidth: true };

  if (
    srcwidth <= 0 ||
    srcheight <= 0 ||
    targetwidth <= 0 ||
    targetheight <= 0
  ) {
    return result;
  }

  // scale to the target width
  var scaleX1 = targetwidth;
  var scaleY1 = (srcheight * targetwidth) / srcwidth;

  // scale to the target height
  var scaleX2 = (srcwidth * targetheight) / srcheight;
  var scaleY2 = targetheight;

  // now figure out which one we should use
  var fScaleOnWidth = scaleX2 > targetwidth;
  if (fScaleOnWidth) {
    fScaleOnWidth = fLetterBox;
  } else {
    fScaleOnWidth = !fLetterBox;
  }

  if (fScaleOnWidth) {
    result.width = Math.floor(scaleX1);
    result.height = Math.floor(scaleY1);
    result.fScaleToTargetWidth = true;
  } else {
    result.width = Math.floor(scaleX2);
    result.height = Math.floor(scaleY2);
    result.fScaleToTargetWidth = false;
  }
  result.targetleft = Math.floor((targetwidth - result.width) / 2);
  result.targettop = Math.floor((targetheight - result.height) / 2);

  return result;
}

function resizeImage(file) {
  return new Promise(function (resolve, reject) {
    try {
      const reader = new FileReader();
      reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');
          // Définir la nouvelle taille
          var result = scaleImage(img.width, img.height, 250, 150, true);
          canvas.height = result.height;
          canvas.width = result.width;
          ctx.drawImage(img, 0, 0, result.width, result.height);
          var resizeImg = canvas.toDataURL('image/jpeg');
          resolve({ data: resizeImg, type: 'jpeg' });
        };
        img.addEventListener('error', function imgOnError(error) {
          reject(error);
        });
        console.log('resized file', e.target.result);
        img.src = e.target.result;
      };
      console.log('resized file', file);
      reader.readAsDataURL(file);
      // When there's an error during load, reject the promise
    } catch (error) {
      console.log('error reszise', error.message);
      reject(error);
    }
  });
}

//
// on a charger une nouvelle image
async function ajoutFichier(files) {
  const response = await resizeImage(files[0]);
  console.log('resize', response);
  emit('changeFile', response.data);
}
function onRejected() {
  // Notify plugin needs to be installed
  // https://quasar.dev/quasar-plugins/notify#Installation
  ihmModule.displayError({ code: 'FUEX', param: K_size_avatar });
}
</script>
<style scoped>
.img-size {
  max-width: 600px;
  max-height: 600px;
}
.img-size-only {
  max-height: 200px !important;
  max-width: 300px !important;
}
</style>
