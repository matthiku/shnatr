<template>
  <div class="card shadow-sm">

    <div class="card-body p-1 p-sm-2 p-md-3">

      <div class="accordion shadow" id="chatrooms">

        <ChatRoom
            v-for="(room, index) in rooms"
            v-if="activeRoom === null || activeRoom === room.id"
            :key="index"
            :room="room"
            :activeRoom="activeRoom"
            class="card mb-1 mb-sm-2 every-chatrooms-card"
          ></ChatRoom>

      </div>
    </div>

    <!-- modal dialog to edit chat room properties or create a new room -->
    <EditRoomProperties />

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import ChatRoom from './components/Room'
import EditRoomProperties from "./components/Edit/RoomProperties";
import swal from 'sweetalert2'

export default {
  middleware: 'auth',

  components: {
    ChatRoom,
    EditRoomProperties
  },

  data () {
    return {
      activeRoom: null
    }
  },

  computed: mapGetters({
    user: 'auth/user',
    users: 'auth/users',
    rooms: 'rooms/rooms'
  }),

  metaInfo () {
    return { title: this.$t('rooms') }
  },

  methods: {
  }
}
</script>
