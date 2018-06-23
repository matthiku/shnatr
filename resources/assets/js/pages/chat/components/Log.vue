<template>
  <div class="card shadow-sm">
    <div class="card-body p-0 p-sm-1 p-md-2 chat-room-body">

      <ShowMessage
          v-for="(message, index) in room.messages"
          :key="index"
          :index="index"
          :message="message"
          :room="room"
        />


      <div
          v-if="!room.messages || (room.messages && !room.messages.length)"
          class="empty" 
        >
        Chat room empty. Send a message!
      </div>


      <EditComposeMessage 
          :room="room"
          @close-all-chats="closeAllChats"
        />


      <ShowSlideshow
          :messages="room.messages"
        />

    </div>
  </div>
</template>


<style>
.chat-room-body {
  background-image: url("/static/paper.gif");
  background-repeat: repeat;  
}
.empty {
  padding: 1rem;
  text-align: center;
}
</style>


<script>
import ShowMessage from './Show/Message'
import ShowSlideshow from './Show/Slideshow'
import EditComposeMessage from './Edit/ComposeMessage'

export default {

  props: ['room'],

  components: {
    ShowMessage,
    ShowSlideshow,
    EditComposeMessage
  },

  methods: {
    closeAllChats () {
      this.$emit('close-all-chats')
    }    
  }
}
</script>

