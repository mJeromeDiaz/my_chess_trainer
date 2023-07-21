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
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

import { Chessground } from 'chessground';
import '../../../node_modules/chessground/assets/chessground.base.css';
import '../../../node_modules/chessground/assets/chessground.brown.css';
import '../../../node_modules/chessground/assets/chessground.cburnett.css';

import { Chess, SQUARES } from 'chess.js'

const puzzleLichess = "00sJb,Q1b2r1k/p2np2p/5bp1/q7/5P2/4B3/PPP3PP/2KR1B1R w - - 1 17,d1d7 a5e1 d7d1 e1e3 c1b1 e3b6,2235,76,97,64,advantage fork long,https://lichess.org/kiuvTFoE#33,Sicilian_Defense,Sicilian_Defense_Dragon_Variation"
const splited = puzzleLichess.split(',');
let orientation = 'white'
const puzzle = {
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
const moveArray  = puzzle.moves.split(' ')
const chessground = ref(null);

const fen = puzzle.fen
const game = new Chess()

let ground = ''


onMounted(() => {
  console.log(game.ascii())
  load()
})

function load(){
  // console.log(game.moves({verbose:true}))

  ground = Chessground(chessground.value, {
    fen: game.fen(),
    orientation: orientation,
    turnColor: 'white',
    movable: {
      color: 'white',
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
    ground.set({
      movable:{
        events: {
          after: playOtherSide()
        }
      }
    })
}

function playOtherSide () {
  return (orig, dest) => {

    if (isPromotion(orig, dest)) {
      game.move({from: orig, to: dest, promotion: 'q'})

    } else {
      game.move({from: orig, to: dest })
    }

    ground.set({
      check: game.isCheck(),
      fen: game.fen(),
      movable: {
        color: toColor(),
        dests: toDests(),
      }
    })
    // console.log(game.ascii())

  }
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

  /**
  console.log({ inCheck:  game.inCheck()})
  console.log({ isCheck:  game.isCheck()})
  console.log({ isCheckmate:  game.isCheckmate()})
  console.log({ isGameOver:  game.isGameOver()})
  console.log({ isDraw:  game.isDraw()})
  console.log({ isStalemate:  game.isStalemate()})
  console.log({ isInsufficientMaterial:  game.isInsufficientMaterial()})
  console.log({ isThreefoldRepetition:  game.isThreefoldRepetition()})
  */

  return (game.turn() === 'w') ? 'white' : 'black';
}

function isPromotion(orig, dest) {
  let filteredPromotions = promotions.filter(move => move.from === orig && move.to === dest)
  console.log(filteredPromotions.length)
  return filteredPromotions.length > 0 // The current movement is a promotion
}


</script>
<style lang="scss">
  body{
    background: $dark;
    color:#f3f3f3
  }
  a{
    color: $accent
  }
  #chessground {
    width: 700px;
    height: 700px;
  }
  cg-board {
    border-radius: 6px;
    background: #bfd1dd, url(../../../public/bg/blue.svg);
  }
</style>
