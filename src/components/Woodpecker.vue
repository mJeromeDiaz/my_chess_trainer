<template>
  <div
    class="container-semi-fluid row q-gutter-md"
    style="height: 100vh; padding: calc(100vh - 800px) 0 0 0"
  >
    <div ref="chessground" id="chessground"></div>

    <div class="col">
      <q-btn
        flat
        rounded
        style="background-color: rgb(117, 60, 240, 0.1)"
        color="negative"
        icon="fas fa-arrow-right"
        @click="loadPuzzle"
        label="Change puzzle"
        :disable="iteration <= puzzles.length"
      ></q-btn>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Chessground } from "chessground";
import { Chess, SQUARES } from "chess.js";
import { toColor, isPromotion, toDests } from "./Utils.js";

const puzzles = [
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
];

let iteration = 0;
let puzzle = {};

const chessground = ref(null);
const fen = puzzle.fen;
const chessjs = new Chess(fen);
let ground = "";

onMounted(() => {
  loadPuzzle();
  ground.set({ fen: chessjs.fen() });
});

function loadPuzzle() {
  puzzle = puzzles[iteration];

  console.log(puzzle.fen);

  ground = Chessground(chessground.value, {
    fen: chessjs.fen(),
    orientation: "white",
    turnColor: "white",
    movable: {
      color: "white",
      free: false,
      dests: toDests(chessjs, SQUARES),
      showDests: true,
      rookCastle: true,
      animation: {
        enabled: true,
        duration: 0.4,
      },
      draggable: {
        enabled: true,
        showGhost: true,
      },
    },
  });
  ground.set({
    movable: {
      events: {
        after: playOtherSide(),
      },
    },
  });
  iteration = iteration + 1;
  console.log(iteration);
}

function playOtherSide() {
  return (orig, dest) => {
    if (nbMoves === nbMovesRealised) {
      result.value = true;
    }

    if (moveArray[nbMovesRealised] !== orig + dest) {
      result.value = false;
      return;
    }
    nbMovesRealised++;
    if (isPromotion(orig, dest, promotions)) {
      chessjs.move({ from: orig, to: dest, promotion: "q" });
    } else {
      chessjs.move({ from: orig, to: dest });
    }

    ground.set({
      check: chessjs.isCheck(),
      fen: chessjs.fen(),
      movable: {
        color: toColor(chessjs),
        dests: toDests(chessjs, SQUARES),
      },
    });

    chessjs.move(moveArray[nbMovesRealised]);

    ground.set({
      check: chessjs.isCheck(),
      fen: chessjs.fen(),
      movable: {
        color: toColor(chessjs),
        dests: toDests(chessjs, SQUARES),
      },
    });
    nbMovesRealised++;
  };
}
</script>
