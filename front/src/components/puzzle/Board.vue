<template>
  <section id="board" class="q-mt-md">
    <div ref="chessground" id="chessground"></div>
    <div style="height:40px" class="">
      <div v-if="error || solution" class="q-gutter-sm">
        <q-btn flat class="btn" color="white" :label="t('retry')" @click="load()" />
        <q-btn flat class="btn" color="white" :label="t(solution ? 'recheckSolution' : 'checkSolution')" @click="lookSolution()"/>
        <q-btn flat class="btn" color="white" :label="t('next')" @click="next()"/>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

import { useI18n } from "vue-i18n";

import { Chess, SQUARES } from 'chess.js'
import { Chessground } from 'chessground';

import '../../../node_modules/chessground/assets/chessground.base.css';
import '../../../node_modules/chessground/assets/chessground.brown.css';
import '../../../node_modules/chessground/assets/chessground.cburnett.css';

const { t } = useI18n();

/**
 * Init varaibles
 */
let chessground = ref(null);
let game = ''
let board = ''

const props = defineProps({
  puzzle: {
    type: Object,
    default() {
      return {}
    }
  }
})

watch(() => props.puzzle, () => {
  reset()
  load()
}, { deep: true })

const emit = defineEmits(['isSuccessful'])

/**
 * Puzzle life
 */
let waintingTime = 200
let moveIteration = ref(0)
let moveArray  = ref([])
let error = ref(false)
let solution = ref(false)

function reset() {
  moveIteration.value = 0
  solution.value = false
  error.value = false

  moveArray.value = props.puzzle.moves.split(' ')
  game = new Chess(props.puzzle.fen)
}

function load(){
  reset()
  board = new Chessground(chessground.value, {
    fen: props.puzzle.fen,
    orientation: game._turn !== 'w' ? 'white' : 'black',
    turnColor: game._turn === 'w' ? 'white' : 'black',
    highlight: {
      lastMove: true,
      check: true
    },
    movable: {
      color: 'both',
      free: false,
      dests: toDests(),
      showDests: true,
      rookCastle: true, // castle by moving the king to the rook
      animation: {
          enabled: true,
          duration: 0.4
        },
        draggable: {
          enabled: true,
          showGhost: true
        }
      }
    })
    robotTurn()
}

function playSound() {
  let file = game?._history[moveIteration.value]?.move?.captured ? 'sound/Capture.mp3' : 'sound/Move.mp3'
  new Audio(file).play()
}

function move(origin, dest) {
  board.move(origin, dest);

  if (isPromotion(origin, dest)) {
    game.move({from: origin, to: dest, promotion: 'q'})
  } else {
    game.move({from: origin, to: dest })
  }
  playSound()
}

function robotTurn(){
    let origin = moveArray.value[moveIteration.value].slice(0, 2)
    let dest = moveArray.value[moveIteration.value].slice(2, 4)

    setTimeout(() => {
      move(origin, dest)

      board.set({
        turnColor: toColor(),
        movable: {
          dests: toDests(),
          events: {
            after: humanTurn()
          }
        }
      })
      moveIteration.value = moveIteration.value + 1
    }, waintingTime);
}



function humanTurn(){
  return (origin, dest) => {
    move(origin, dest)

    if(origin === moveArray.value[moveIteration.value].slice(0, 2) && dest === moveArray.value[moveIteration.value].slice(2, 4)){

      moveIteration.value = moveIteration.value + 1

      // le puzzle est terminé
      if(moveIteration.value === moveArray.value.length) {


        let audio = new Audio('sound/puzzleIsDone.mp3')
        audio.play()
        emit('isSuccessful', !error.value)

      } else {
        board.set({
          turnColor: toColor(),
          movable: {
            dests: toDests(),
            events: {
              after: robotTurn(),
            }
          }
        })
      }
    } else {
      // le puzzle est raté
      let audio = new Audio('sound/puzzleIsMissed.mp3')
      audio.play()
      error.value = true
      emit('isSuccessful', !error.value)

    }
  }
}

function lookSolution(){
    reset()
    error.value = false
    solution.value = true

    board = Chessground(chessground.value, {
    fen: props.puzzle.fen,
    orientation: game._turn !== 'w' ? 'white' : 'black',
    turnColor: game._turn === 'w' ? 'white' : 'black',
    highlight: {
      lastMove: true,
      check: true
    },
    movable: {
      color: 'both',
      free: false,
      dests: toDests(),
      showDests: true,
      rookCastle: true, // castle by moving the king to the rook
      animation: {
          enabled: true,
          duration: 0.4
        },
        draggable: {
          enabled: true,
          showGhost: true
        }
      }
    })


    moveArray.value.forEach((m, i)=>{
      setTimeout(() => {
        let origin = moveArray.value[moveIteration.value].slice(0, 2)
        let dest = moveArray.value[moveIteration.value].slice(2, 4)
        move(origin, dest)

        board.set({
          turnColor: toColor(),
          movable: {
            dests: toDests(),
            events: {
              after: humanTurn()
            }
          }
        })
        moveIteration.value = moveIteration.value + 1
      }, i * waintingTime * 1.2);
    })
}

function toDests() {
  const dests = new Map();
  SQUARES.forEach(s => {
    const ms = game.moves({square: s, verbose: true});
    if (ms.length) dests.set(s, ms.map(m => m.to));
  });
  return dests;
}

let promotions = []
function toColor() {
  let moves = game.moves({verbose: true})
  for (let move of moves) {
    if (move.promotion) {
      promotions.push(move)
    }
  }
  return (game.turn() === 'w') ? 'white' : 'black';
}

function isPromotion(orig, dest) {
  let filteredPromotions = promotions.filter(move => move.from === orig && move.to === dest)
  return filteredPromotions.length > 0 // The current movement is a promotion
}


function timer(where, tps){
  where.innerText = '00:00'

  setInterval(() => {
    let minutes = parseInt(tps / 60, 10)
    let secondes = parseInt(tps % 60, 10)

    minutes = minutes < 10 ? "0" + minutes : minutes
    secondes = secondes < 10 ? "0" + secondes : secondes

    where.innerText = `${minutes}:${secondes}`
    tps = tps + 1
  }, 1000)
}

onMounted(()=>{
  load()
})


</script>
<style lang="scss">
cg-board {
  border-radius: 6px;
  background: #bfd1dd url(../../../public/bg/blue.svg);
}

.btn{
  background: rgb(255, 255, 255, 0.03)
}
</style>
