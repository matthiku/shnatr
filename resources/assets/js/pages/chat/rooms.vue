<template>
  <div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10 col-md-12 mw-1k px-0 px-sm-1">
      <div class="card shadow-sm">

        <div class="card-body chatroom-canvas p-0 p-sm-1 p-md-2 p-lg-3 p-xl-4">

          <div class="accordion shadow" id="chatrooms">

            <div
                v-for="(room, index) in rooms"
                :key="index"
                :room="room"
                class="card mb-1 mb-sm-2 every-chatrooms-card"
              >{{room.name}}</div>

          </div>
        </div>

      </div>

    </div>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import swal from 'sweetalert2'

export default {
  middleware: 'auth',

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
