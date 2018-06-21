import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'

import moment from 'moment-timezone'

import '~/plugins'
import '~/components'

Vue.config.productionTip = false

// Provide the moment library to all components
moment.tz.setDefault('UTC')
// add moment to the Vue prototype, so that we can use it in all components!
Object.defineProperty(Vue.prototype, '$moment', {
  get () {
    return this.$root.moment
  }
})

/* eslint-disable no-new */
/* jshint ignore:start */
new Vue({
  i18n,
  store,
  router,
  ...App
})
/* jshint ignore:end */

// Try to enable Service Worker TODO: (temporarily disabled!)
if ('service(remove me!)Worker' in navigator) {
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
