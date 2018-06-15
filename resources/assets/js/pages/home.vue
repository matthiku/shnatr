<template>
  <card :title="$t('home')">

    <div class="mb-3">
      {{ $t('you_are_logged_in') }}
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

export default {
  middleware: 'auth',

  computed: mapGetters({
    user: 'auth/user'
  }),

  metaInfo () {
    return { title: this.$t('home') }
  },

  methods: {
    sendEmail () {
      this.$store.dispatch('auth/sendVerifyEmail')
    }
  }
}
</script>
