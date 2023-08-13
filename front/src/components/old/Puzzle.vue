<template>
  <div class="container-semi-fluid row q-gutter-md" style="height: 100vh; padding: calc(100vh - 800px) 0 0 0">
    <div class="">
      <div ref="chessground" id="chessground"></div>
    </div>
    <div class="text-left">
      <div>moves : {{ puzzle.moves }}</div>
      <div>id : {{ puzzle.id }}</div>
      <div>url puzzle : <a :href="'https://lichess.org/training/'+ puzzle.id" title="lien vers le puzzle">lien vers le puzzle</a></div>
      <div>url partie: <a :href="puzzle.gameUrl" title="lien vers le puzzle">lien vers le puzzle</a></div>
      <div> fen : {{ puzzle.fen }}</div>
      <q-banner inline-actions class="text-white bg-red" v-if="error">
        Perdu !
        <template v-slot:action>
          <q-btn flat color="white" label="Retry ?" @click="load()" />
          <q-btn flat color="white" label="Voir la solution" @click="undo()"/>
          <q-btn flat color="white" label="Passer" />
        </template>
      </q-banner>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

import { Chess, SQUARES } from 'chess.js'
import { Chessground } from 'chessground';
import '../../node_modules/chessground/assets/chessground.base.css';
import '../../node_modules/chessground/assets/chessground.brown.css';
import '../../node_modules/chessground/assets/chessground.cburnett.css';


const chessground = ref(null);
let game = ''
let board = ''

let props = defineProps({
  puzzleLichess: {
    type: String,
    default: '"00sJb,Q1b2r1k/p2np2p/5bp1/q7/5P2/4B3/PPP3PP/2KR1B1R w - - 1 17,d1d7 a5e1 d7d1 e1e3 c1b1 e3b6,2235,76,97,64,advantage fork long,https://lichess.org/kiuvTFoE#33,Sicilian_Defense,Sicilian_Defense_Dragon_Variation"'
  }
})

let emit = defineEmits(['updatePuzzle'])

const splited = ref(props.puzzleLichess.split(','));
const puzzle = ref({
  id: splited.value[0],
  fen: splited.value[1],
  moves: splited.value[2],
  rating: splited.value[3],
  ratingDeviation: splited.value[4],
  popularity: splited.value[5],
  nbPlays: splited.value[6],
  themes: splited.value[7],
  gameUrl: splited.value[8],
  openingFamily: splited.value[9],
  openingVariation: splited.value[10]
})

const moveArray  = puzzle.value.moves.split(' ')
const moveIteration = ref(0)
let error = ref(false)
const waintingTime = 600


function playSound() {
  let file = game._history[moveIteration.value].move.captured ? 'sound/Capture.mp3' : 'sound/Move.mp3'
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
  console.log(game)
}

function robotTurn(){
    let origin = moveArray[moveIteration.value].slice(0, 2)
    let dest = moveArray[moveIteration.value].slice(2, 4)

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

    if(origin === moveArray[moveIteration.value].slice(0, 2) && dest === moveArray[moveIteration.value].slice(2, 4)){

      moveIteration.value = moveIteration.value + 1

      if(moveIteration.value === moveArray.length) {
        emit('updatePuzzle')
        let audio = new Audio('sound/puzzleIsDone.mp3')
        audio.play()
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
      let audio = new Audio('sound/puzzleIsMissed.mp3')
      audio.play()
      error.value = true
    }
  }
}

function reset(){
  game = new Chess(puzzle.value.fen)
  moveIteration.value = 0
  error.value = false
}


function load(){
  reset()
  board = Chessground(chessground.value, {
    fen: puzzle.value.fen,
    orientation: game._turn !== 'w' ? 'white' : 'black',
    turnColor: toColor(),
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


onMounted(() => {
  load()
})


</script>
<style lang="scss">
  cg-board {
    border-radius: 6px;
    background: #bfd1dd url(../../public/bg/blue.svg);
  }
</style>
