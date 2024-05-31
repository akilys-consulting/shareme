<template>
  <q-layout view="hHh Lpr lff">
    <navbar />

    <q-page-container>
      <div class="q-pa-md">
        <router-view />
      </div>
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">

import navbar from 'src/layouts/detail/NavBar.vue'


</script>
<style>
#body {
  background-image: url("src/assets/fond.jpg");
  color:black;
}
</style>
