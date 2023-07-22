<template>
  <div class="container-semi-fluid flex flex-center" style="height: 100vh">
    <h1 class="title q-mb-none">WoodPecker</h1>
    <div class="row q-gutter-md">

      <section id="board" class="">
        <div ref="chessground" id="chessground"></div>
      </section>

      <section id="sidebar" class="text-left">
        <div class="text-h6">

          <div>{{ puzzleIteration }} / {{ puzzles.length }} puzzles</div>
          <div class="">Global timer :
            <span id="globalTimer"></span>
          </div>
          <div class="">puzzle timer :
            <span id="timer"></span>
          </div>
        </div>

        <div>Moves : {{ puzzle.moves }}</div>
        <div>Id : {{ puzzle.id }}</div>
        <div>Url puzzle : <a :href="'https://lichess.org/training/'+ puzzle.id" title="lien vers le puzzle">lien vers le puzzle</a></div>
        <div>Url partie: <a :href="puzzle.gameUrl" title="lien vers le puzzle">lien vers le puzzle</a></div>
        <div>Fen : {{ puzzle.fen }}</div>
        <div v-if="solution || error" class="q-mt-md">
          <q-banner inline-actions class="text-white" :class="error ? 'bg-red' : 'bg-primary'">
            <template v-slot:action>
              <div v-if="error">
                <q-btn flat color="white" label="Réessayer" @click="load()" />
                <q-btn flat color="white" label="Voir la solution" @click="lookAtTheSolution()"/>
                <q-btn flat color="white" label="Passer" @click="next()"/>
              </div>
              <div v-if="solution">
                <q-btn flat color="white" label="Réessayer" @click="load()" />
                <q-btn flat color="white" label="Revoir la solution" @click="lookAtTheSolution()"/>
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
let puzzles = ref([
  "00sJ9,r3r1k1/p4ppp/2p2n2/1p6/3P1qb1/2NQR3/PPB2PP1/R1B3K1 w - - 5 18,e3g3 e8e1 g1h2 e1c1 a1c1 f4h6 h2g1 h6c1,2671,105,87,325,advantage attraction fork middlegame sacrifice veryLong,https://lichess.org/gyFeQsOE#35,French_Defense,French_Defense_Exchange_Variation",
  "00sJb,Q1b2r1k/p2np2p/5bp1/q7/5P2/4B3/PPP3PP/2KR1B1R w - - 1 17,d1d7 a5e1 d7d1 e1e3 c1b1 e3b6,2235,76,97,64,advantage fork long,https://lichess.org/kiuvTFoE#33,Sicilian_Defense,Sicilian_Defense_Dragon_Variation",
  "00sHx,q3k1nr/1pp1nQpp/3p4/1P2p3/4P3/B1PP1b2/B5PP/5K2 b k - 0 17,e8d7 a2e6 d7d8 f7f8,1760,80,83,72,mate mateIn2 middlegame short,https://lichess.org/yyznGmXs/black#34,Italian_Game,Italian_Game_Classical_Variation",
  "00sO1,1k1r4/pp3pp1/2p1p3/4b3/P3n1P1/8/KPP2PN1/3rBR1R b - - 2 31,b8c7 e1a5 b7b6 f1d1,998,85,94,293,advantage discoveredAttack master middlegame short,https://lichess.org/vsfFkG0s/black#62",
])
let puzzleIteration = ref(0)
let puzzle = ref('')

/**
 * Puzzle life
 */
let moveIteration = ref(0)
let moveArray  = ref([])
let error = ref(false)
let waintingTime = 600

function reset() {
  moveIteration.value = 0
  solution.value = false
  error.value = false

  let splited = puzzles.value[puzzleIteration.value].split(',');

  puzzle.value = {
    id: splited[0],
    fen: splited[1],
    moves: splited[2],
    rating: splited[3],
    ratingDeviation: splited[4],
    popularity: splited[5],
    nbPlays: splited[6],
    themes: splited[7],
    gameUrl: splited[8],
    openingFamily: splited[9],
    openingVariation: splited[10]
  }
  moveArray.value = puzzle.value.moves.split(' ')
  game = new Chess(puzzle.value.fen)
  timer(document.getElementById("timer"), 0)
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
          // la le puzzle est Correct
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
      let audio = new Audio('sound/puzzleIsMissed.mp3')
      audio.play()
      error.value = true
    }
  }
}

let solution = ref(false)
function lookAtTheSolution(){
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



onMounted(() => {
  load()
  timer(document.getElementById("globalTimer"), 0)
})


</script>
<style lang="scss">
.title{
  background: -webkit-linear-gradient(315deg,#42d392 25%, $primary);
  background-clip: border-box;
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 900;
}
.bg{
  background: -webkit-linear-gradient(315deg,#42d392 25%, $primary);
}
cg-board {
  border-radius: 6px;
  background: #bfd1dd url(../../public/bg/blue.svg);
}
</style>
