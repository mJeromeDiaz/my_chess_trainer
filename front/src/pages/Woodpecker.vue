<template>
  <div class="container" style="height: 100vh">
    <canvas id="canvas"></canvas>
    <h2 class="title q-mb-none">WoodPecker</h2>
    <div class="row q-gutter-md">
      <div v-if="puzzle.id">
        <board :puzzle="puzzle" @isSuccessful="next"></board>
      </div>


      <section class="col">
        <div class="text-h5">{{ puzzleIteration }} / {{ puzzles.length }} puzzles</div>
        <div class="text-h1">
          <div class=""><span id="globalTimer"></span></div>
        </div>

        <graph v-if="store.puzzles.length > 0"></graph>

      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ConfettiGenerator from "confetti-js";

import board from "../components/puzzle/Board.vue"
import graph from "../components/woodpecker/Graph.vue"
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

    let confettiSettings = { target: 'canvas', max: 200  };
    let confetti = new ConfettiGenerator(confettiSettings);
    confetti.render();
    return;
  }
  puzzle.value = puzzles.value[puzzleIteration.value]
}

function getPuzzles() {
  return axios.get(`${process.env.API_URL}puzzles?itemsPerPage=2&page=1`).then(response=>{
    puzzles.value = shuffle(response.data['hydra:member'] || response.data)
    store.puzzles = puzzles.value

    puzzle.value = puzzles.value[puzzleIteration.value]
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

#canvas{
  position: fixed;
  inset: 0;
  z-index: 9999;
}
</style>
