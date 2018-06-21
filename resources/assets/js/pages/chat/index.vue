<template>
  <div class="row">
    <div class="col-md-3 d-none d-sm-block">
      <card class="chat-cards">
        <ul class="nav flex-column nav-pills">
          <li v-for="tab in tabs" :key="tab.route" class="nav-item">
            <router-link :to="{ name: tab.route }" class="nav-link" active-class="active">
              <fa :icon="tab.icon" fixed-width/>
              <span class="d-none d-md-inline" v-html="tab.name"></span>
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
  </div>
</template>

<script>
export default {
  middleware: 'auth',

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
        }
      ]
    }
  }
}
</script>

<style>
.chat-card .card-body {
  padding: 0;
}
</style>
