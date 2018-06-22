<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <!-- <card :title="$t('login')"> -->
      <card>

        <!-- Oauth Provider Login Buttons -->
        <span v-if="githubAuth || googleAuth || facebookAuth">
          <div class="row">
            <label class="col-md-3 col-form-label text-md-right">
              {{ $t('login_with') }}:
            </label>
            <div class="col-md-7 d-none d-sm-inline">
              <div class="d-flex justify-content-between">
                <login-with-google/>
                <login-with-github/>
                <login-with-facebook/>
              </div>
            </div>
            <div class="col-md-7 d-sm-none">
              <div class="d-flex justify-content-between">
                <login-with-google small="small"/>
                <login-with-github small="small"/>
                <login-with-facebook small="small"/>
              </div>
            </div>
          </div>
          <div class="mt-3">
            {{ $t('or_login_with_pw')}}
          </div>
        </span>
        
        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
          <!-- Email -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('email') }}</label>
            <div class="col-md-7">
              <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
              <has-error :form="form" field="email"/>
            </div>
          </div>

          <!-- Password -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">{{ $t('password') }}</label>
            <div class="col-md-7">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
              <has-error :form="form" field="password"/>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="form-group row">
            <div class="col-md-3"/>
            <div class="col-md-7 d-flex">
              <checkbox v-model="remember" name="remember">
                {{ $t('remember_me') }}
              </checkbox>

              <router-link :to="{ name: 'password.request' }" class="small ml-auto my-auto">
                {{ $t('forgot_password') }}
              </router-link>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex">
              <!-- Submit Button -->
              <v-button :loading="form.busy">
                {{ $t('login') }}
              </v-button>
              <router-link :to="{ name: 'register' }" class="small ml-auto my-auto">
                {{ $t('register') }}
              </router-link>
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import LoginWithGoogle from '~/components/LoginWithGoogle'
import LoginWithGithub from '~/components/LoginWithGithub'
import LoginWithFacebook from '~/components/LoginWithFacebook'

export default {
  middleware: 'guest',

  components: {
    LoginWithGoogle,
    LoginWithGithub,
    LoginWithFacebook
  },

  metaInfo () {
    return { title: this.$t('login') }
  },

  computed: {
    githubAuth: () => window.config.githubAuth,
    facebookAuth: () => window.config.facebookAuth,
    googleAuth: () => window.config.googleAuth,
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    }
  }
}
</script>
