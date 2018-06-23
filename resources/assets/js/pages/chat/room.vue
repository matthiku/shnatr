/**
 * Show individual Room
 */
<template>
  <div class="card shadow-sm">

    <RoomHeader :room="activeRoom"/>

    <div class="card-body p-0 p-sm-1 p-md-2">

      <ChatLog :room="activeRoom"/>

    </div>
  </div>
</template>


<script>
import { mapGetters } from 'vuex'
import RoomHeader from './components/Header'
import ChatLog from './components/Log'

export default {
  props: ['room_id'],

  middleware: 'auth',

  components: {
    ChatLog,
    RoomHeader
  },

  metaInfo () {
    return { title: this.pageTitle }
  },

  computed: {
    activeRoom () {
      if (this.room_id) {
        return this.room = this.$store.getters["rooms/room"](this.room_id)
      }
      // if no room number was provided, use the newest room
      if (!this.room_id && this.latestRoom) {
        return this.room = this.$store.getters["rooms/room"](this.latestRoom.id)
      }
      // no active room found, return to all rooms
      this.$router.push({name: 'chat.rooms'})
      return null      
    },

    pageTitle () {
      return this.room ? this.room.name : '@'
    },

    ...mapGetters({
      latestRoom: 'rooms/latestRoom'
    })
  }
}
</script>
