<template>
  <q-layout view="hHh LpR fFf">
    <div id="dashboard">

      <q-header reveal class="text-white">
        <q-toolbar class="">
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
          <q-toolbar-title class="text-bold">My Chess Trainer</q-toolbar-title>
        </q-toolbar>
      </q-header>

      <q-drawer v-model="leftDrawerOpen" side="left" overlay elevated class="bg-dark">
      <!-- drawer content -->
      </q-drawer>

      <q-page-container class="content">
        <router-view />
      </q-page-container>
    </div>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'

const leftDrawerOpen = ref(false)
function toggleLeftDrawer () {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>

<style scoped>
.bg-dark-dashboard{
  background: #22272E;
}
</style>
