<template>
  <div ref="chessground" id="chessground"></div>
  <br /><br />
  {{ puzzleref }}
</template>

<script setup>
import { ref, onMounted, toRef, computed } from "vue";
import { Chessground } from "chessground";
import { Chess, SQUARES } from "chess.js";
import { toColor, isPromotion, toDests } from "../Utils.js";

const props = defineProps({
  puzzle: {
    type: Object,
    default() {
      return {
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
      };
    },
    required: true,
  },
});

// const puzzleref = toRef(props, "puzzle");
const puzzleref = computed(() => props.puzzle);
const chessground = ref(null);
let fen;
let chessjs;
let ground = "";

// const moveArray = puzzleref.value.moves.split(" ");
// let nbMovesRealised = 0;
// let result = ref();

let promotions = [];

onMounted(() => {
  fen = puzzleref.value.fen;
  console.log(puzzleref.value);
  chessjs = new Chess(fen);

  load();
  ground.set({ fen: chessjs.fen() });
});

function load() {
  ground = Chessground(chessground.value, {
    fen: chessjs.fen(),
    orientation: toColor(chessjs),
    turnColor: toColor(chessjs),
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
}

function playOtherSide() {
  return (orig, dest) => {
    // if (puzzleref.value.moves.length === nbMovesRealised) {
    //   result.value = true;
    // }

    // if (moveArray[nbMovesRealised] !== orig + dest) {
    //   result.value = false;
    //   return;
    // }
    // nbMovesRealised++;
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

    // chessjs.move(moveArray[nbMovesRealised]);

    //   ground.set({
    //     check: chessjs.isCheck(),
    //     fen: chessjs.fen(),
    //     movable: {
    //       color: toColor(chessjs),
    //       dests: toDests(chessjs, SQUARES),
    //     },
    //   });
    //   nbMovesRealised++;
  };
}
</script>

<style lang="scss">
cg-board {
  border-radius: 6px;
  background-color: #bfd1dd;
  background: url(../../../public/bg/blue.svg);
}
</style>
