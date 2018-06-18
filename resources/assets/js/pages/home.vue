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
          'Email sent!',
          'A new verification email was sent to ' + this.user.email,
          'success'
        )
      }
    }
  }
}
</script>
