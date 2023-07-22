<template>
  <q-layout view="hHh LpR fFf">
    <q-header reveal class="bg-dark text-white">
      <q-toolbar class="container-semi-fluid">
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
          <q-toolbar-title class="text-bold">My Chess Trainer</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" side="left" overlay elevated class="bg-dark">
      <!-- drawer content -->
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'

const leftDrawerOpen = ref(false)
function toggleLeftDrawer () {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>
