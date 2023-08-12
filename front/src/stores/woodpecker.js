import { defineStore } from 'pinia';

export const woodpeckerStore = defineStore('woodpecker', {
  state: () => {
    return {
      startedAt: '',
      endedAt: '',
      puzzles: []
    }
  },
  actions: {

  }
})
