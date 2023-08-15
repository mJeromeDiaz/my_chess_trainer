<template>
  <div class="row flex flex-center h-100 container q-col-gutter-lg" v-if="puzzle?.id">
    <div class="row">
      <puzzleBoard :puzzle="puzzle"></puzzleBoard>
      <div class="col q-mt-lg q-ml-lg">
        <div>Id : {{ puzzle.lichessId }}</div>
        <div><a target="_blank" :href="'https://lichess.org/training/'+ puzzle.lichessId" :title="t('puzzleLink')">{{  t('puzzleLink')  }}</a></div>
        <div><a :href="puzzle.gameUrl" :title="t('gameLink')">{{ t('gameLink') }}</a></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

import puzzleBoard from "../components/puzzle/Board.vue";

import { useI18n } from "vue-i18n";
import { useRoute } from 'vue-router'

const { t } = useI18n();
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
