
<template>
  <q-drawer v-model="afficherMenu" overlay>
    <profil />
    <q-separator spaced />
    <q-scroll-area class="fit">
      <q-list>
        <q-item clickable v-ripple :to="{ name: 'add_event' }">
          <q-item-section avatar>
            <q-icon color="primary" name="login" />
          </q-item-section>

          <q-item-section>Ajouter un évènement</q-item-section>
        </q-item>

        <q-item clickable v-ripple>
          <q-item-section avatar>
            <q-icon color="primary" name="content_paste_go" />
          </q-item-section>

          <q-item-section>Importer des évènements</q-item-section>
        </q-item>

      </q-list>
    </q-scroll-area>
    <!-- drawer content -->
  </q-drawer>
</template>


<script lang="ts" setup>

import { onMounted, ref, computed } from 'vue'
import profil from 'src/components/admin/GestProfil.vue'

// déclaration des paramètres d'entrée
const props = defineProps<{
  display: boolean
}>()

const afficherMenu = computed(() => props.display)
const drawer = ref(false)


onMounted(() => {
  drawer.value = false
})

</script>

<style scoped></style>
