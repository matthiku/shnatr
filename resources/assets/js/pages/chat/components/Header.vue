/**
 * Show Room Header
 */
<template>
  <div class="card-header px-1 pt-1 pb-1 p-sm-1 p-md-2 my-0" :id="'heading-'+room.id">

    <div @click="toggleRoom" 
        class="d-flex justify-content-between mb-0 w-100 p-0 cursor-pointer chatroom-header">

        <!-- edit properties and show room name -->
        <span class="room-props-and-name">
          <fa icon="cog" fixed-width
              title="room settings dialog"
              v-if="room.id !== 0"
              @click.stop="editRoom(room)"
              data-toggle="modal" data-target="#chatRoomProperties"
            />

          <span v-if="room.name" class="room-name ml-1">{{ room.name }}</span>
        </span>

        <!-- show room members inline on wider screens -->
        <ShowRoomMembers
            class="d-none d-md-inline"
            :room="room" :user="user"
          />

      <!-- show messages counter -->
      <span class="nowrap overflow-hidden">
        <small class="mr-1 mr-sm-2">{{ $moment(room.updated_at).fromNow() }}</small>
        <!-- <span v-if="unreadMessages + arrivedMessages"
            class="badge badge-danger badge-pill">{{ unreadMessages }}</span> -->
        <span class="badge badge-secondary badge-pill mr-1">{{ room.messages ? room.messages.length : 0 }}</span>
      </span>
    </div>

    <!-- on smaller screens show room members on extra line -->
    <ShowRoomMembers
        class="d-md-none d-block d-flex flex-nowrap justify-content-center overflow-hidden"
        :room="room" :user="user"
      />

  </div>
</template>


<style>
.chatroom-header {
  opacity: 0.6;
  font-size: small;  
}
.chatroom-header:hover {
  opacity: 1;
  font-size: inherit;
}
.room-props-and-name {
  text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;  
}
.room-name {
  font-family: 'Times New Roman', Times, serif;
  font-size: larger;
  color:darkred;
}
.nowrap {
  white-space: nowrap;
}
</style>


<script>
import ShowRoomMembers from './Show/RoomMembers'
import { mapGetters } from 'vuex'

export default {
  props: ['room'],

  components: {
    ShowRoomMembers
  },

  computed: {
    roomName () {
      if (this.room.users.length !== 2 && this.room.name)
        return this.room.name
      if (this.room.users.length === 2 || !this.room.name)
        return this.room.users.find(el => el.id !== this.user.id).username
      return '(unnamed)'
    },
    unreadMessages () {
      const usersReadingProgress = this.room.users.find(usr => usr.id === this.user.id)
      if (!usersReadingProgress) return 0
      let lastReadMessageIdx = this.room.messages.findIndex(
        msg => msg.updated_at > usersReadingProgress.pivot.updated_at
      )
      if (lastReadMessageIdx < 0) return 0
      // console.log(usersReadingProgress.pivot.updated_at, lastReadMessageIdx)
      return this.room.messages.length - lastReadMessageIdx
    // },
    // arrivedMessages () {
    //   let num = 0
    //   this.newMessagesArrived.forEach(el => {
    //       if (el.room_id === this.room.id) num += 1
    //   })
    //   return num      
    // },
    // newMessagesArrived () {
    //   return this.$store.state.chat.newMessagesArrived
    },
    ...mapGetters({
      user: 'auth/user',
      rooms: 'rooms/rooms'
    })
  },

  mounted () {
    // check if user's last typing dates are outdated
    setTimeout(() => {
      this.checkTypingState()
    }, 9000);
  },

  methods: {
    checkTypingState () {
      this.room.users.forEach(usr => {
        if (usr.typing) {
          let diff = Math.floor((new Date() - usr.typing))
          if ( diff > 9000 ) usr.typing = false
        }
      });      
      setTimeout(() => {
        this.checkTypingState()
      }, 9000);
    },

    // toggle between all rooms and single room
    toggleRoom () {
      if (this.$route.name === 'chat.room')
        this.$router.push({name: 'chat.rooms'})
      else
        this.$router.push({ name: 'chat.room', params: {room_id: this.room.id}})
    },

    editRoom (room) {
      // edit properties of or settings for this room
      let members = []
      room.users.map(el => members.push(el.id))
      this.$store.commit('shared/setNewRoomMembers', members)
      this.$store.commit('shared/setDialog',
        {
          what: 'updateRoom',
          option: room.id,
          roomName: room.name
        }
      )
    },

    delayedCleanUp () {
      let elem = document.getElementById('collapse-0')
      elem.classList.remove('show')
      // make sure 'leftover' rooms are removed; ie. rooms
      //    of which the current user is no longer a member
      this.$store.commit('cleanUpRooms')
    },

    closeAllChats () {
      this.$emit('close-all-chats')
    }
    
  }

}
</script>
