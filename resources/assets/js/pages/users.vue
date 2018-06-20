<template>
  <card :title="$t('people')">

    <div class="mb-3">
      <ul class="list-group">

        <li v-for="user in users" :key="user.id"
            class="list-group-item d-flex justify-content-between align-items-center">
          <span>
            <img :src="user.photo_url" class="rounded" alt="user avatar" height="35px">
            {{ user.name }}
          </span>

          <button>
            <fa icon="comments" fixed-width/>
          </button>
        </li>

      </ul>
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
    users: 'auth/users'
  }),

  metaInfo () {
    return { title: this.$t('people') }
  },

  methods: {
  }
}
</script>
