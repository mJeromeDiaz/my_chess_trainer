<template>
  <div ref="chessground" id="chessground"></div>
  {{ puzzle }}
</template>

<script setup>
import { ref, onMounted, defineProps } from "vue";
import { Chessground } from "chessground";
import { Chess, SQUARES } from "chess.js";

import { toColor, isPromotion, toDests } from "../Utils.js";

const props = defineProps({
  puzzle: Object,
});
const puzzle = ref(props.puzzle);
const puzzlez = ref(props.puzzle.value);
console.log(puzzlez);
const game = puzzle.value;

const chessground = ref(null);
const fen = game.fen;
const chessjs = new Chess(fen);

let ground = "";

const moveArray = game.moves.split(" ");
let nbMoves = game.moves.length;
let nbMovesRealised = 0;
let result = ref();
let promotions = [];

onMounted(() => {
  load();
  ground.set({ fen: chessjs.fen() });
});

function load() {
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

<style lang="scss">
cg-board {
  border-radius: 6px;
  background-color: #bfd1dd;
  background: url(../../../public/bg/blue.svg);
}
</style>
