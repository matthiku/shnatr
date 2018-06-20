<template>
  <card>
    <form
        @submit.prevent="update"
        @keydown="form.onKeydown($event)"
        class="px-1"
      >
      <alert-success :form="form" :message="$t('info_updated')"/>

      <!-- Name -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
        <div class="col-md-7">
          <input v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              class="form-control"
              type="text" name="name">
          <has-error :form="form" field="name"/>
        </div>
      </div>

      <!-- Email -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
        <div class="col-md-7">
          <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
          <has-error :form="form" field="email" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group row">
        <div class="col-md-9 ml-md-auto">
          <v-button
              :loading="form.busy"
              :disabled="!formChanged"
              type="success">
            <fa v-if="formChanged" icon="check" fixed-width/>
            {{ $t('update') }}</v-button>
        </div>
      </div>

      <!-- Photo -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label d-none d-md-inline text-md-right">{{ $t('photo') }}</label>
        <div class="d-flex pl-3">
          <img :src="form.photo_url" alt="avatar" height="55px">
          <span class="mx-1">{{ $t('about_avatar') }}
            <a target="new" :title="$t('help')" :href="`http://${locale}.gravatar.com/`">Gravatar</a>
          </span>
        </div>
      </div>

    </form>
  </card>
</template>

<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    formChanged: false,
    form: new Form({
      name: '',
      email: '',
      photo_url: ''
    })
  }),

  updated () {
    this.formChanged = true
  },

  computed: mapGetters({
    user: 'auth/user',
    locale: 'lang/locale'
  }),

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      const { data } = await this.form.patch('/api/settings/profile')

      this.$store.dispatch('auth/updateUser', { user: data })
    }
  }
}
</script>
