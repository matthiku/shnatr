import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import Echo from 'laravel-echo'

import moment from 'moment-timezone'

import '~/plugins'
import '~/components'

Vue.config.productionTip = false

// Provide the moment library to all components
moment.tz.setDefault('UTC')
// add moment to the Vue prototype, so that we can use it in all components!
Vue.prototype.$moment = moment

/* eslint-disable no-new */
/* jshint ignore:start */
new Vue({
  i18n,
  store,
  router,
  ...App
})
/* jshint ignore:end */

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows us to easily build robust real-time web applications.
 */
window.Pusher = require('pusher-js')

// Enable pusher logging - not in production!
if (process.env.APP_ENV === 'local') {
  window.Pusher.logToConsole = true
}

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  encrypted: false,
  authEndpoint: '/broadcasting/auth',
  disableStats: false
})

// Try to enable Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/sw.js').then(function (registration) {
      // Registration was successful
      window.console.log('ServiceWorker registration successful with scope: ', registration.scope)
    }, function (err) {
      // registration failed :(
      window.console.log('ServiceWorker registration failed: ', err)
    })
  })
}

// Listen to the "Install to Home Screen" event
window.addEventListener('beforeinstallprompt', (event) => {
  // Prevent Chrome 67 and earlier from automatically showing the prompt
  event.preventDefault()
  // Stash the event so it can be triggered later.
  store.commit('shared/setDeferredPrompt', event)
  return false
})
