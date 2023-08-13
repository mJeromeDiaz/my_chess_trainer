<template>
  <div class="row flex flex-center h-100 container q-col-gutter-lg" v-if="puzzle?.id">

    <div class="col-3 q-mt-md bg-teal">
      <div v-if="solution || error" >
        <q-banner inline-actions class="text-white" :class="error ? 'bg-red' : 'bg-primary'">
          <template v-slot:action>
            <div v-if="error">
              <q-btn flat color="white" label="Réessayer" @click="load()" />
              <q-btn flat color="white" label="Voir la solution" @click="lookSolution()"/>
              <q-btn flat color="white" label="Passer" @click="next()"/>
            </div>
            <div v-if="solution">
              <q-btn flat color="white" label="Réessayer" @click="load()" />
              <q-btn flat color="white" label="Revoir la solution" @click="lookSolution()"/>
              <q-btn flat color="white" label="Passer" @click="next()" v-if="puzzleIteration === puzzles.length"/>
            </div>
          </template>
        </q-banner>
      </div>
    </div>

    <div class="col">
      <puzzleBoard :puzzle="puzzle" @isSuccessful="isSuccessful"></puzzleBoard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

import puzzleBoard from "../components/puzzle/Board.vue";
import { loadRouteLocation } from 'vue-router';

/**
 * Puzzles life
 */
let puzzle = ref({})
let error = ref(false)
let solution = ref(false)

function isSuccessful(success){
  if(success) {
    getPuzzle(Math.floor(Math.random() * 200))
  } else {
    error.value = true
  }
}

function getPuzzle(id){
  axios.get(`${process.env.API_URL}puzzles/${id}`).then(response=>{
    puzzle.value = response.data
    console.log(puzzle.value.id)
  })
}

onMounted(()=>{
  getPuzzle(Math.floor(Math.random() * 200))
})
</script>
