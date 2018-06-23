/**
 * Show individual Room
 */
<template>
  <div class="card">
    <div class="card-body chat-room-body p-0 p-sm-1 p-md-2 p-lg-3 p-xl-4">

      <ChatLog v-if="room" :room="room"/>

    </div>
  </div>
</template>


<style>
.chat-room-body {
  background-image: url("/static/paper.gif");
  background-repeat: repeat;  
}
</style>


<script>
import { mapGetters } from 'vuex'
import ChatLog from './components/Log'

export default {
  props: ['room_id'],

  components: {
    ChatLog
  },

  data () {
    return {
      room: null
    }
  },

  created () {
    // if no room number was provided, use the newest room
    if (!this.room_id && this.latestRoom) {
      this.room = this.$store.getters["rooms/room"](this.latestRoom.id)
    }
    if (this.room_id) {
      this.room = this.$store.getters["rooms/room"](this.room_id)
    }
  },

  computed: mapGetters({
      latestRoom: 'rooms/latestRoom'
    })

}
</script>
