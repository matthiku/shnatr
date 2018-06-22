<template>
  <nav class="navbar navbar-expand-sm navbar-light bg-white">
    <div class="container">

      <!-- app icon and home button -->
      <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand">
        <img src="/static/icons/icon-128x128.png" alt="logo" height="30px">
      </router-link>

      <!-- main app menu -->
      <ul class="navbar-nav mr-auto">
        <li v-if="!user" class="nav-item"
            v-html="$t($route.name)"
          ></li>
        <li v-else class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-dark"
              v-html="$t($route.name)"
              @click="$refs.navbarToggler.classList.remove('show')"
              data-toggle="dropdown"
              data-display="dynamic"
              href="#" id="navbarDropdown" role="button"
              aria-haspopup="true" aria-expanded="false">
          </a>
          <div class="dropdown-menu position-absolute" aria-labelledby="navbarDropdown">

            <router-link :to="{name: 'chat.users'}" class="dropdown-item">
              <fa icon="users" fixed-width/>
              <span v-html="$t('people')"></span>
            </router-link>

            <router-link :to="{name: 'chat.rooms'}" class="dropdown-item">
              <fa icon="comments" fixed-width/>
              <span v-html="$t('rooms')"></span>
            </router-link>
            <div class="dropdown-divider"></div>

            <router-link :to="{name: 'home'}" class="dropdown-item">
              <fa icon="cog" fixed-width/>
              <span v-html="$t('home')"></span>
            </router-link>

            <a class="dropdown-item" href="#">New Chat</a>
          </div>
        </li>
      </ul>


      <!-- burger menu on small devices -->
      <button class="navbar-toggler"
          ref="burgerMenuToggler"
          id="burgerMenuToggler"
          type="button" data-toggle="collapse" data-target="#navbarToggler" 
          aria-controls="navbarToggler" aria-expanded="false">
        <span class="navbar-toggler-icon"/>
      </button>

      <div id="navbarToggler" 
          ref="navbarToggler"
          class="collapse navbar-collapse">

        <!-- language selection dropdown menu only on home page! -->
        <ul class="navbar-nav"
           :class="{'d-none': $route.name !== 'home'}">
          <locale-dropdown/>
        </ul>

        <ul class="navbar-nav ml-auto">

          <!-- Authenticated -->
          <li v-if="user" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark"
               href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img :src="user.photo_url" class="rounded-circle profile-photo mr-1">
              {{ user.name }}
            </a>
            <div class="dropdown-menu">
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                <fa icon="cog" fixed-width/>
                {{ $t('settings') }}
              </router-link>

              <a href="#" class="dropdown-item pl-3" @click.prevent="installApp" v-show="showInstallPrompt">
                <fa icon="mobile-alt" fixed-width/>
                {{ $t('install_app') }}
              </a>

              <div class="dropdown-divider"/>
              <a href="#" class="dropdown-item pl-3" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width/>
                {{ $t('logout') }}
              </a>
            </div>
          </li>

          <!-- Guest -->
          <template v-else>
            <li class="nav-item" @click="$refs.burgerMenuToggler.click()">
              <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                {{ $t('login') }}
              </router-link>
            </li>
            <li class="nav-item" @click="$refs.burgerMenuToggler.click()">
              <router-link :to="{ name: 'register' }" class="nav-link" active-class="active">
                {{ $t('register') }}
              </router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from './LocaleDropdown'

export default {
  components: {
    LocaleDropdown
  },

  data: () => ({
    appName: window.config.appName,
    showInstallPrompt: false
  }),

  computed: mapGetters({
    user: 'auth/user',
    deferredPrompt: 'shared/deferredPrompt'
  }),

  watch: {
    deferredPrompt (val) {
      if (val) {
        this.showInstallPrompt = true
      }
    }
  },

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')
      // await this.$store.dispatch('rooms/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    },
    installApp () {
      if (this.deferredPrompt) {
        // hide our user interface that shows our A2HS button
        this.showInstallPrompt = false
        // Show the install prompt
        this.deferredPrompt.prompt()
        // Wait for the user to respond to the prompt
        this.deferredPrompt.userChoice
          .then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
              console.log('User accepted the A2HS prompt')
            } else {
              console.log('User dismissed the A2HS prompt')
            }
            this.$store.commit('shared/setDeferredPrompt', null)
          })
      }
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
#install-app-item {
  display: none;
  cursor: pointer;
}
</style>
