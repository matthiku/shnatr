<template>
  <div v-if="room" 
      class="card shadow-sm"
      ref="scrollContainer"
    >
    <div class="card-body p-0 p-sm-1 p-md-2 chat-room-body scroll-container">

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
.scroll-container {
  position: absolute;
  top: 0px;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow-y: scroll; 
}
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

  mounted () {
    let vm = this
    window.addEventListener('resize', function() {
      vm.calculateRoomHeight()
    });
    this.calculateRoomHeight() // do it once initially
  },

  methods: {
    calculateRoomHeight () {
      // Calculate max possible height of message container to allow for vertical scrolling
      let scrollContainer = this.$refs.scrollContainer
      if (!scrollContainer) return // not available when component is in transition
      let elemPosTop = scrollContainer.getBoundingClientRect().top 
      let maxHeight = window.innerHeight - elemPosTop - 10
      this.$refs.scrollContainer.style.height = `${maxHeight}px`
    },
    closeAllChats () {
      this.$emit('close-all-chats')
    }    
  }
}
</script>

