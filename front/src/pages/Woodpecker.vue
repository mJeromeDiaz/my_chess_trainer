<template>
  <div
    class="container-semi-fluid row q-gutter-md"
    style="height: 100vh; padding: calc(100vh - 800px) 0 0 0"
  >
    <MyBoard :puzzle="puzzle" />
    <q-btn
      @click="changePuzzle()"
      color="primary"
      label="+1"
      :disable="iteration === puzzles.length - 1"
    ></q-btn>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import MyBoard from "../components/Puzzle.vue";
const puzzles = ref([
  {
    id: "00sHx",
    fen: "q3k1nr/1pp1nQpp/3p4/1P2p3/4P3/B1PP1b2/B5PP/5K2 b k - 0 17",
    moves: "e8d7 a2e6 d7d8 f7f8",
    rating: "1760",
    ratingDeviation: "80",
    popularity: "83",
    nbPlays: "72",
    themes: "mate mateIn2 middlegame",
    gameUrl: "https://lichess.org/yyznGmXs/black#34",
    openingFamily: "Italian_Game",
    openingVariation: "Italian_Game_Classical_Variation",
  },
  {
    id: "00sJ9",
    fen: "r3r1k1/p4ppp/2p2n2/1p6/3P1qb1/2NQR3/PPB2PP1/R1B3K1 w - - 5 18",
    moves: "e3g3 e8e1 g1h2 e1c1 a1c1 f4h6 h2g1 h6c1",
    rating: "2671",
    ratingDeviation: "105",
    popularity: "87",
    nbPlays: "325",
    themes: "advantage attraction fork middlegame sacrifice veryLong",
    gameUrl: "https://lichess.org/gyFeQsOE#35",
    openingFamily: "French_Defense",
    openingVariation: "French_Defense_Exchange_Variation",
  },
]);

let iteration = ref(0);
let puzzle = ref(puzzles.value[0]);

function changePuzzle() {
  iteration.value = iteration.value + 1;
  puzzle.value = ref(puzzles.value[1]);
}
</script>
