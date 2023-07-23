<template>
  <q-layout view="hHh LpR fFf">
    <q-header reveal class="bg text-white">
      <q-toolbar class="">
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
          <q-toolbar-title class="text-bold">My Chess Trainer</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" side="left" overlay elevated class="bg-grey-10">
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
