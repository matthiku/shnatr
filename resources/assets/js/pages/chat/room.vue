/**
 * Show individual Room
 */
<template>
  <div class="card shadow-sm">

    <RoomHeader :room="room"/>

    <div class="card-body p-0 p-sm-1 p-md-2">

      <ChatLog :room="room"/>

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

  data () {
    return {
      room: {}
    }
  },

  metaInfo () {
    return { title: this.pageTitle }
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

  computed: {
    pageTitle () {
      return this.room.name
    },
    ...mapGetters({
      latestRoom: 'rooms/latestRoom'
    })
  }
}
</script>
