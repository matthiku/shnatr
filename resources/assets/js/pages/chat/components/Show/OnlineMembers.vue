<template>
  <div class="d-flex flex-nowrap overflow-hidden">

    <span class="d-none d-sm-inline mr-1">Online</span>
    <fa icon="search" fixed-width
        class="align-middle"/>

    <!-- show names as clickable badges - click opens new chat -->
    <a href="#" v-for="(u, idx) in onlineUsers" :key="idx"
        v-if="user.id !== u.id"
        @click="launchNewRoomModal(u.id)"
        :title="'click to start chatting with ' + u.name"
      >
      <img v-if="u.avatar"
          class="float-right user-avatar rounded-circle"
          width="35px"
          :src="u.avatar">
      <span v-else
        class="p-1 bg-primary avatar-helper text-white rounded-circle mr-2"
        >{{ u.name.substr(0,1).toUpperCase() }}</span>
    </a>

    <!-- // show icon if no-one is online -->
    <strong v-if="onlineUsers.length < 2">
      <fa icon="user-slash" fixed-width/>
    </strong>

  </div>
</template>


<style>
  .overflow-hidden {
    overflow: hidden;
  }
  .avatar-helper {
    width: 35px;
    height: 35px;
    display: inline-block;
    text-align: center;
  }
</style>



<script>
export default {

  props: ['onlineUsers', 'user'],

  methods: {
    launchNewRoomModal (user_id) {
      this.$store.commit('setNewRoomMembers', [user_id])
      this.$store.commit('setDialog', {what: 'createNewRoom', option: 'single'})
    }    
  }
}
</script>
