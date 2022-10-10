<template>
  <div class="container row q-gutter-md" style="height: 100vh; padding: calc(100vh - 800px) 0 0 0">
    <div class="">
      <div ref="chessground" id="chessground"></div>
    </div>
    <div class="text-left">
      <div>moves : {{ puzzle.moves }}</div>
      <div>id : {{ puzzle.id }}</div>
      <div>url puzzle : <a :href="'https://lichess.org/training/'+ puzzle.id" title="lien vers le puzzle">lien vers le puzzle</a></div>
      <div>url partie: <a :href="puzzle.gameUrl" title="lien vers le puzzle">lien vers le puzzle</a></div>
      <div> fen : {{ puzzle.fen }}</div>
      <div> fen : {{ fen }}</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

import { Chessground } from 'chessground';
import '../../node_modules/chessground/assets/chessground.base.css';
import '../../node_modules/chessground/assets/chessground.brown.css';
import '../../node_modules/chessground/assets/chessground.cburnett.css';

import { Chess, SQUARES } from 'chess.js'

const game = new Chess()
const puzzleLichess = "00sHx,q3k1nr/1pp1nQpp/3p4/1P2p3/4P3/B1PP1b2/B5PP/5K2 b k - 0 17,e8d7 a2e6 d7d8 f7f8,1760,80,83,72,mate mateIn2 middlegame short,https://lichess.org/yyznGmXs/black#34,Italian_Game,Italian_Game_Classical_Variation"
const splited = puzzleLichess.split(',');

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
const fen = "q5nr/1ppknQpp/3p4/1P2p3/4P3/B1PP1b2/B5PP/5K2 w - - 1 18"

const config = {
  fen: puzzle.fen,
  check: false,
  turnColor: 'white',
  highlight: {
    lastMove: true,
    check: true
  },
  movable: {
    color: 'white', // only allow white pieces to be moved (it's white's turn to start)
    free: false, // don't allow movement anywhere ...
    dests: toDests(game), // ... only allow these moves. Accepts {square: [list of squares]} in Algebraic notation
    rookCastle: true // castle by moving the king to the rook
  },
  draggable: {
    showGhost: true
  }
};
const chessground = ref(null);

onMounted(() => {
  const ground = Chessground(chessground.value, config);
})

function toDests(chess) {
  const dests = new Map();
  SQUARES.forEach(s => {
    const ms = chess.moves({square: s, verbose: true});
    if (ms.length) dests.set(s, ms.map(m => m.to));
  });
  return dests;
}

function toColor(chess) {
  return (chess.turn() === 'w') ? 'white' : 'black';
}

function playOtherSide(cg, chess) {
  return (orig, dest) => {
    chess.move({from: orig, to: dest});
    cg.set({
      turnColor: toColor(chess),
      movable: {
        color: toColor(chess),
        dests: toDests(chess)
      }
    });
  };
}

function aiPlay(cg, chess, delay, firstMove) {
  return (orig, dest) => {
    chess.move({from: orig, to: dest});
    setTimeout(() => {
      const moves = chess.moves({verbose:true});
      const move = firstMove ? moves[0] : moves[Math.floor(Math.random() * moves.length)];
      chess.move(move.san);
      cg.move(move.from, move.to);
      cg.set({
        turnColor: toColor(chess),
        movable: {
          color: toColor(chess),
          dests: toDests(chess)
        }
      });
      cg.playPremove();
    }, delay);
  };
}



</script>
<style>
  body{
    background:#282a36;
    color:#f3f3f3
  }
  a{
    color: #bd93f9
  }
  #chessground {
    width: 700px;
    height: 700px;
  }
  cg-board {
    border-radius: 6px;
    background-color: #bfd1dd;
    background: url(../../public/bg/blue.svg);
  }
</style>
