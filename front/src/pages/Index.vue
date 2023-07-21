<template>
  <div class="">
    <board></board>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import board from '../components/Board.vue'

export default defineComponent({
  name: "PageIndex",
  components:{
    board
  }
});
</script>

