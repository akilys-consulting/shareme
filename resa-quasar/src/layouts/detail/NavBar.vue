
<template>
  <q-header elevated class="bg-white">
    <q-toolbar flat class="q-mr-sm">

      <q-toolbar-title>
        <q-avatar @click="drawer = !drawer">
          <img src="~assets/icon.png" alt="APPIS">
        </q-avatar>
        ENAC
      </q-toolbar-title>
    </q-toolbar>
  </q-header>

  <displayMenu :display="drawer" />
</template>


<script lang="ts" setup>

import { onMounted, ref } from 'vue'

import displayMenu from 'src/layouts/MenuApp.vue'
const drawer = ref(false)

onMounted(() => {
  drawer.value = false
})

</script>

<style scoped></style>
