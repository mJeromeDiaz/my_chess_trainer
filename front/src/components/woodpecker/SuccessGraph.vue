<template>
  <div class="full-width">
    <div class="row">
      <div class="" v-for="puzzle in puzzles" :key="puzzle.id" style="width: 12px; height: 12px; margin: 3px; border-radius: 4px;" :class="icons[puzzle?.isSuccessful]">&nbsp;</div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { woodpeckerStore } from '../../stores/woodpecker'
const store = woodpeckerStore()

const icons = {
  true: 'bg-positive',
  false: 'bg-negative',
  undefined: 'bg-grey-4'
}

const puzzles = reactive(store.puzzles)

</script>
