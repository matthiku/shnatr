<template>
  <card :title="$t('home')">

    <div class="mb-3">
      <p>
        {{ $t('hello') }}, {{ user.name }}!
      </p>

    </div>

    <div v-if="user && !user.verifyToken">

      <router-link
          :to="{name: 'chat.users'}"
          class="btn btn-sm btn-primary mr-2 mb-2"
        >
        <fa icon="users" fixed-width/>
        {{ $t('people' ) }}
      </router-link>

      <router-link
          :to="{name: 'chat.rooms'}"
          class="btn btn-sm btn-secondary mr-2">
        <fa icon="comments" fixed-width/>
        <span v-html="$t('rooms')"></span>
      </router-link>

      <router-link
          :to="{name: 'chat.room'}"
          class="btn btn-sm btn-info">
        <fa icon="comment" fixed-width/>
        {{ $t('last_room') }}
      </router-link>

    </div>

    <div v-if="user && user.verifyToken">
      <hr>

      {{ $t('account_not_verified') }}.
      {{ $t('check_email') }}

      <!-- Re-Send Button -->
      <div class="mt-1" @click="sendEmail">
        <v-button nativeType="button">
          {{ $t('send_confirmation_link') }}
        </v-button>
      </div>
    </div>

  </card>
</template>


<script>
import { mapGetters } from 'vuex'
import swal from 'sweetalert2'

export default {
  middleware: 'auth',

  computed: mapGetters({
    user: 'auth/user',
    verifyEmailSent: 'auth/verifyEmailSent'
  }),

  metaInfo () {
    return { title: this.$t('home') }
  },

  methods: {
    async sendEmail () {
      await this.$store.dispatch('auth/sendVerifyEmail')

      if (this.verifyEmailSent) {
        swal(
          $t('email_sent!'),
          $t('email_sent_to') + ' ' + this.user.email,
          'success'
        )
      }
    }
  }
}
</script>
