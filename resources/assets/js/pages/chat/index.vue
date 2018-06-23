<template>
  <div class="row">
    <div class="col-md-3 d-none d-sm-block">
      <card class="chat-cards">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <fa :icon="tab.icon" fixed-width/>
              <span v-html="tab.name"></span>
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="col-md-9">
      <transition name="fade" mode="out-in">
        <router-view/>
      </transition>
    </div>

    <!-- modal dialog to edit chat room properties or create a new room -->
    <EditRoomProperties />

  </div>
</template>


<style>
.chat-card .card-body {
  padding: 0;
}
</style>


<script>
import EditRoomProperties from "./components/Edit/RoomProperties";

export default {
  middleware: 'auth',

  components: { EditRoomProperties },

  computed: {
    tabs () {
      return [
        {
          icon: 'users',
          name: this.$t('users'),
          route: 'chat.users'
        },
        {
          icon: 'comments',
          name: this.$t('rooms'),
          route: 'chat.rooms'
        },
        {
          icon: 'comment',
          name: this.$t('room'),
          route: 'chat.room'
        }
      ]
    }
  }
}
</script>
