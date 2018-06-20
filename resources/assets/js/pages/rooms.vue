<template>
  <card>

    <div class="card-header">
      <span class="btn btn-outline-dark" disabled>
        {{ $t('your_rooms') }}
      </span>

      <router-link
          :to="{name: 'users'}"
          class="float-right btn btn-sm btn-primary"
        >
        {{ $t('people' ) }}
      </router-link>
    </div>

    <div class="mb-3">
      rooms ...
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
    users: 'auth/users',
    users: 'auth/rooms'
  }),

  metaInfo () {
    return { title: this.$t('rooms') }
  },

  methods: {
  }
}
</script>
