<template>
  <div class="container-semi-fluid column" style="height: 100vh">
    <h2 class="title q-mb-none">WoodPecker</h2>
    <div class="row q-gutter-md">

      <section id="board" class="" >
        <div ref="chessground" id="chessground"></div>
      </section>


        <section id="sidebar" class="text-left">
          <div class="text-h5">{{ puzzleIteration }} / {{ puzzles.length }} puzzles</div>
          <div class="text-h1">
            <div class=""><span id="globalTimer"></span>
          </div>

        </div>
        <div>
            <successGraph></successGraph>
        </div>
        <div>Id : {{ puzzle.lichessId }}</div>
        <div>Url puzzle : <a target="_blank" :href="'https://lichess.org/training/'+ puzzle.lichessId" title="lien vers le puzzle">lien vers le puzzle</a></div>
        <div>Url partie: <a :href="puzzle.gameUrl" title="lien vers le puzzle">lien vers le puzzle</a></div>
        <div v-if="solution || error" class="q-mt-md">
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
      </section>
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
import axios from 'axios';
import successGraph from './woodpecker/successGraph.vue'

import { woodpeckerStore } from '../stores/woodpecker.js'
const store = woodpeckerStore()

/**
 * Init varaibles
 */
let chessground = ref(null);
let game = ''
let board = ''

/**
 * Puzzles life
 */
// a récupérer de l'api
let puzzles = ref([])
let puzzleIteration = ref(0)
let puzzle = ref('')

/**
 * Puzzle life
 */
let moveIteration = ref(0)
let moveArray  = ref([])
let error = ref(false)
let waintingTime = 200

function reset() {
  moveIteration.value = 0
  solution.value = false
  error.value = false


  puzzle.value = puzzles.value[puzzleIteration.value]
  moveArray.value = puzzle.value.moves.split(' ')
  game = new Chess(puzzle.value.fen)
  // timer(document.getElementById("timer"), 0)
}

function load(){
  reset()
  board = new Chessground(chessground.value, {
    fen: puzzle.value.fen,
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

function next(){
  error.value = false
  solution.value = false
  puzzleIteration.value = puzzleIteration.value + 1
  load()
}

function humanTurn(){
  return (origin, dest) => {
    move(origin, dest)

    if(origin === moveArray.value[moveIteration.value].slice(0, 2) && dest === moveArray.value[moveIteration.value].slice(2, 4)){

      moveIteration.value = moveIteration.value + 1

      // le puzzle est terminé
      if(moveIteration.value === moveArray.value.length) {
        puzzleIteration.value = puzzleIteration.value + 1

        if(puzzleIteration.value === puzzles.value.length) {
          // la session est terminée
          let audio = new Audio('sound/successfullyEndSession.mp3')
          audio.play()


        } else {
          let storePuzzle = store.puzzles.find(el => el.id = puzzle.value.id)
          // le puzzle est Correct
          if(storePuzzle?.isSuccessful !== false) {
            storePuzzle.isSuccessful = true
          }
          let audio = new Audio('sound/puzzleIsDone.mp3')
          audio.play()

          setTimeout(() => load(), waintingTime);
        }

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
      store.puzzles.find(el => el.id = puzzle.value.id).isSuccessful = false
      let audio = new Audio('sound/puzzleIsMissed.mp3')
      audio.play()
      error.value = true
    }
  }
}

let solution = ref(false)
function lookSolution(){
    reset()
    error.value = false
    solution.value = true

    board = Chessground(chessground.value, {
    fen: puzzle.value.fen,
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

function getPuzzles() {
  return axios.get('https://localhost:8000/api/puzzles?page=1').then(response=>{
    puzzles.value = response.data['hydra:member'] || response.data
    store.puzzles = response.data['hydra:member'] || response.data
    store.startedAt = new Date()
  })
}

onMounted(() => {
  getPuzzles().then(()=>{
    load()
    timer(document.getElementById("globalTimer"), 0)
  })
})


</script>
<style lang="scss">


cg-board {
  border-radius: 6px;
  background: #bfd1dd url(../../public/bg/blue.svg);
}
</style>
