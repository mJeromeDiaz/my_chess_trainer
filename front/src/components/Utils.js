import '../../node_modules/chessground/assets/chessground.base.css';
import '../../node_modules/chessground/assets/chessground.brown.css';
import '../../node_modules/chessground/assets/chessground.cburnett.css';

function toColor(chessjs) {
  let moves = chessjs.moves({verbose: true})
  for (let move of moves) {
    if (move.promotion) {
      promotions.push(move)
    }
  }  return (chessjs.turn() === 'w') ? 'white' : 'black';
}


function isPromotion(orig, dest, promotions) {
  let filteredPromotions = promotions.filter(move => move.from === orig && move.to === dest)
  console.log(filteredPromotions.length)
  return filteredPromotions.length > 0 // The current movement is a promotion
}


function toDests(chessjs, SQUARES) {
  const dests = new Map();
  SQUARES.forEach(s => {
    const ms = chessjs.moves({square: s, verbose: true});
    if (ms.length) dests.set(s, ms.map(m => m.to));
  });
  return dests;
}

function shuffle (array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
};

export { toColor , isPromotion,  toDests, shuffle }
