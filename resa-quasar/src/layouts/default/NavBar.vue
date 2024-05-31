
<template>
  <q-header elevated class="bg-white text-primary text-color ">
    <q-toolbar  flat class="q-mr-sm">

      <q-toolbar-title  class="text-center">
        <q-btn flat   color="primary" size="xl" icon="search" class="q-mr-sm" />
        <q-btn flat :to="{ name: 'connexion' }" color="secondary" size="xl" icon="person" class="q-mr-sm" />
      </q-toolbar-title>

    </q-toolbar>
  </q-header>

</template>


<script lang="ts" setup>

</script>

<style scoped>
.button:hover {
  background-color: rgb(129, 12, 12);
  transition: 0.7s;
}

.btn-active {
  background-color: rgb(129, 12, 12);
}
</style>
