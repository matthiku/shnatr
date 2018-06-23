<template>
  <div class="card shadow-sm">

    <div class="card-body p-1 p-sm-2 p-md-3">

      <div class="shadow">

        <RoomHeader
            v-for="(room, index) in rooms"
            :key="index"
            :room="room"
            class="card mb-1 mb-sm-2 every-chatrooms-card"
          ></RoomHeader>

      </div>
    </div>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import RoomHeader from './components/Header'
import swal from 'sweetalert2'

export default {
  middleware: 'auth',

  components: {
    RoomHeader
  },

  data () {
    return {
      pageTitle: this.$t('rooms'),
      onlineUsers: 1,
      newMessagesArrived: 0
    }
  },

  computed: mapGetters({
    user: 'auth/user',
    users: 'auth/users',
    rooms: 'rooms/rooms'
  }),

  metaInfo () {
    return { title: this.pageTitle }
  },

  watch: {
    newMessagesArrived (val) {
      // update page title when this entity changes
      this.setPageTitle()
      // open the chatroom of the first new message if no room is currently open
      if (val.length && !this.activeRoom) {
        this.activeRoom = val[0].room.id
      }
    },

    onlineUsers () {
      this.setPageTitle()
    },

    roomsTodo () {
      this.rooms.map(room => {

        // check if room is already connected: 
        //          check if the entity "window.Echo.connector.channels"
        //          contains an object with name 'private-{chatroomName}.chatroom.{id}'
        if (window.Echo.connector.channels[`private-${this.chatroomName}.chatroom.${room.id}`]) return

        // start listening to our backend broadcast channel for this Chat Room
        window.Echo.private(this.chatroomName + '.chatroom.' + room.id)

          .listen('MessagePosted', e => {
            if (e.message) {
              let msg = e.message
              msg.user = e.user
              room.messages.push(msg)
              // make sure the room gets in first place now
              room.updated_at = msg.updated_at
              // show warning for new messages (but not this user's own msg)
              // REMOVED: and not when the room is already open! "&& msg.room_id !== this.activeRoom"
              if (e.user.id !== this.user.id) {
                this.$store.commit('addToNewMessagesArrived', msg)
                // TODO: play a sound!
                // add background notification!
                if (! ('Notification' in window)) {
                  alert('Web Notification is not supported')
                } else {
                  Notification.requestPermission( permission => {
                    let notification = new Notification(
                      'New message from ' + msg.user.username,
                      { body: `Room: ${msg.room.name},\nText: ${msg.message}` }
                    )
                    notification.onclick = () => {
                      // TODO: open this specific room!
                      window.open(window.location.href)
                    }
                  })
                }
              }
              this.$store.commit('sortRooms') // make sure the room list is refreshed
            } else {
              window.console.warn(e)
            }
          })

          .listen('MessageUpdated', e => {
            if (e.message) {
              let msg = e.message
              msg.user = e.user
              // find and replace the old message
              let idx = room.messages.findIndex(el => el.id === msg.id)
              room.messages[idx] = msg
              // only to trigger the reactivity!
              room.messages.push(msg) 
              room.messages.pop()
            } else {
              window.console.warn(e)
            }
          })

          .listen('RoomTyping', e => {
            if (e.user) {
              room.users.forEach(usr => {
                if (usr.id === e.user.id)
                  this.$set(usr, 'typing', new Date())
              })
            } else {
              window.console.warn(e)
            }
          })

          .on('pusher:subscription_succeeded', e => {
            window.console.log(`Subscription to chatroom ${room.id} was successful`)
          })        
      })

      this.onlyOneRoom() // check if we have only one room

      // now check if all private chatrooms are still in use
      let privChannels = window.Echo.connector.channels // object with all current channels
      for (const key in privChannels) {
        if (privChannels.hasOwnProperty(key)) {
          // key should be in the format 'private-chatroom.[id]'
          let chName = key.split('.')
          if (chName.length !== 2 || chName[0] !== 'private-chatroom') continue
          if (this.rooms.find(el => el.id === parseInt(chName[1]))) continue
          window.Echo.leave('chatroom.' + chName[1])
        }
      }

      this.setPageTitle()
    }
  },

  methods: {
    setPageTitle () {
      let windowTitle = '(idle)'
      if (this.onlineUsers.length > 1) {
        if (this.onlineUsers.length === 2) {
          let otherUser = this.onlineUsers.find(el => el.id !== this.user.id).username
          windowTitle = ` - ${otherUser} is online`
        }
        else
          windowTitle = ' -' + (this.onlineUsers.length-1) + ' users online'
      }

      let newMsgCount = this.newMessagesArrived.length
      // if there are new messages, show name of first chat containing new messages
      if (newMsgCount) {
        windowTitle = this.newMessagesArrived[0].room.name
        newMsgCount = `(${newMsgCount}) `
      } else {
        newMsgCount = ''
      }

      this.pageTitle = `${newMsgCount}${windowTitle}`

      // make sure we are properly connected to the presence channel
      // this.safetyCheck()
    }
  }
}
</script>
