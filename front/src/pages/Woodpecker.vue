<template>
  <div class="container-semi-fluid column" style="height: 100vh">
    <h2 class="title q-mb-none">WoodPecker</h2>
    <div class="row q-gutter-md">
      <div v-if="puzzle.id">
        <board :puzzle="puzzle" @isSuccessful="next"></board>
      </div>


      <section id="sidebar" class="text-left">
        <div class="text-h5">{{ puzzleIteration }} / {{ puzzles.length }} puzzles</div>
        <div class="text-h1">
          <div class=""><span id="globalTimer"></span></div>
        </div>

        <successGraph></successGraph>

      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

import board from "../components/puzzle/Board.vue"
import successGraph from "../components/woodpecker/SuccessGraph.vue"
import { shuffle } from "../components/Utils.js"

import { woodpeckerStore } from '../stores/woodpecker.js'
const store = woodpeckerStore()

/**
 * Puzzles life
 */
// a récupérer de l'api
let puzzles = ref([])
let puzzleIteration = ref(0)
let puzzle = ref({})


function next(success) {
  puzzles.value[puzzleIteration.value].isSuccessful = success
  puzzleIteration.value = puzzleIteration.value + 1

  if(puzzleIteration.value === puzzles.value.length){
    let audio = new Audio('sound/successfullyEndSession.mp3');
    audio.play();
    store.endedAt = new Date()
    store.getDuration()
    return;
  }
  puzzle.value = puzzles.value[puzzleIteration.value]
}

function getPuzzles() {
  return axios.get(`${process.env.API_URL}puzzles?itemsPerPage=100&page=1`).then(response=>{
    puzzles.value = shuffle(response.data['hydra:member'] || response.data)
    puzzle.value = puzzles.value[puzzleIteration.value]
    store.puzzles = puzzles.value
    store.startedAt = new Date()
  })
}

onMounted(() => {
  getPuzzles()
})


</script>
<style lang="scss">
cg-board {
  border-radius: 6px;
  background: #bfd1dd url(../../public/bg/blue.svg);
}
</style>
../Utils.js
