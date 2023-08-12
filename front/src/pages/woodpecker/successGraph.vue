<template>
  <div class="full-width">
    <div class="column q-gutter-md">
      <div class="" v-for="puzzle in store.puzzles" :key="puzzle.id">
        <q-icon :name="icons[puzzle?.isSuccessful].name" :color="icons[puzzle?.isSuccessful].color"></q-icon>
      </div>
    </div>
  </div>
</template>

<script setup>

import { woodpeckerStore } from '../../stores/woodpecker'
const store = woodpeckerStore()

const icons = {
  true: { name: 'far fa-check-circle', color: 'teal'},
  false: { name: 'far fa-times-circle', color: 'red'},
  undefined: { name: 'fas fa-circle', color: 'white'}
}



</script>
