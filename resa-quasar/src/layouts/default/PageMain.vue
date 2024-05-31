<template>
  <q-layout color="black" style="height: 100%; width: 100%" view="hHh Lpr lff">
    <navbar />

    <q-page-container>
      <div class="q-pa-md">
        <router-view />
      </div>
    </q-page-container>

  </q-layout>
</template>

<script setup lang="ts">

import navbar from 'src/layouts/default/NavBar.vue'


</script>
<style>

</style>
