<template>
  <div class="full-width q-my-md">
    <div class="row">
      <div
        v-for="puzzle in puzzles" :key="puzzle.id"
        @click="openPuzzle(puzzle)"
        style="width: 12px; height: 12px; margin: 3px; border-radius: 4px;"
        :class="bubbleCss[puzzle?.isSuccessful]"
      >&nbsp;</div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

import { woodpeckerStore } from '../../stores/woodpecker'
import { useRouter } from 'vue-router'

const store = woodpeckerStore()
const router = useRouter();

function openPuzzle(puzzle) {
  if(puzzle?.isSuccessful === false){
    const routeData = router.resolve({name: 'puzzle', params: { id:  puzzle.id }});
    window.open(routeData.href, '_blank');
  }
}

const bubbleCss = {
  true: 'bg-positive',
  false: 'bg-negative cursor-pointer',
  undefined: 'bg-grey-10'
}

const puzzles = reactive(store.puzzles)



</script>
