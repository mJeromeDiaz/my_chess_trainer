import { defineStore } from 'pinia';

export const woodpeckerStore = defineStore('woodpecker', {
  state: () => {
    return {
      startedAt: '',
      endedAt: '',
      duration: { hours: '', minutes: '', secondes:''},
      puzzles: []
    }
  },
  actions: {
    getDuration() {
      let diffInMilliseconds = this.endedAt - this.startedAt;
      this.duration.hours = Math.trunc(diffInMilliseconds / 3600000);
      this.duration.minutes = Math.trunc((diffInMilliseconds % 3600000) / 60000);
      this.duration.seconds = Math.trunc((diffInMilliseconds % 60000) / 1000);
    }
  }
})
