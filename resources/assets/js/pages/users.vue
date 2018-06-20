<template>
  <card>

    <div class="input-group mb-3">

      <div class="input-group-prepend">
        <span class="input-group-text" id="search-field">
          <fa icon="search" fixed-width/>
          <span class="ml-1 d-none d-sm-inline">{{ $t('search') }}</span>
        </span>
      </div>

      <input v-model="search"
          type="text"
          ref="searchField"
          class="form-control"
          :placeholder="$t('search_string')"
          aria-label="Username" aria-describedby="search-field">

      <div class="input-group-append">
        <button @click="search = ''"
            class="btn btn-outline-secondary" 
            type="button">X</button>
      </div>
    </div>

    <div class="mb-3">
      <ul class="list-group">

        <li v-for="user in filteredUsers" :key="user.id"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
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
  
  data () {
    return {
      search: ''
    }
  },

  computed: {
    filteredUsers () {
      if (this.search) {
        return this.users.filter(u => {
          return u.name.indexOf(this.search) > -1
        })
      }
      return this.users
    },
    ...mapGetters({
      user: 'auth/user',
      users: 'auth/users'
    })
  },

  metaInfo () {
    return { title: this.$t('people') }
  },

  mounted () {
    this.$refs.searchField.focus()
  },

  methods: {
  }
}
</script>
