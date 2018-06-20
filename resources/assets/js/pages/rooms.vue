<template>
  <card>

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
    rooms: 'rooms/rooms'
  }),

  metaInfo () {
    return { title: this.$t('rooms') }
  },

  methods: {
  }
}
</script>
