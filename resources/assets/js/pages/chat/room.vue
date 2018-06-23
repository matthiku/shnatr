/**
 * Show individual Room
 */
<template>
  <div class="card shadow-sm">

    <RoomHeader
        :room="room"
        class="card-header mb-1 mb-sm-2 every-chatrooms-card"
      />

    <div class="card-body chat-room-body p-0 p-sm-1 p-md-2 p-lg-3 p-xl-4">

      <ChatLog :room="room"/>

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
import RoomHeader from './components/Header'
import ChatLog from './components/Log'

export default {
  props: ['room_id'],

  components: {
    ChatLog,
    RoomHeader
  },

  data () {
    return {
      room: {}
    }
  },

  created () {
    if (this.room_id) {
      this.room = this.$store.getters["rooms/room"](this.room_id)
      return
    }
    // if no room number was provided, use the newest room
    if (!this.room_id && this.latestRoom) {
      this.room = this.$store.getters["rooms/room"](this.latestRoom.id)
      return
    }
    // no active room found, return to all rooms
    this.$router.push({name: 'chat.rooms'})
  },

  computed: mapGetters({
      latestRoom: 'rooms/latestRoom'
    })

}
</script>
