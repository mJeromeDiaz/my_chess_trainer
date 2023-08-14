<template>
  <div class="row flex flex-center h-100 container q-col-gutter-lg" v-if="puzzle?.id">
    <div class="">
      <puzzleBoard :puzzle="puzzle"></puzzleBoard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

import puzzleBoard from "../components/puzzle/Board.vue";

import { useRoute } from 'vue-router'

const route = useRoute();
const puzzleId = route.params.id

/**
 * Puzzles life
 */
let puzzle = ref({})

function getPuzzle(id){
  axios.get(`${process.env.API_URL}puzzles/${id}`).then(response=>{
    puzzle.value = response.data
  })
}

onMounted(()=>{
  getPuzzle(puzzleId)
})
</script>
