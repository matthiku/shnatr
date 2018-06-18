import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'

import '~/plugins'
import '~/components'

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
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
  console.log('beforeinstallprompt fired!')
  // Prevent Chrome 67 and earlier from automatically showing the prompt
  event.preventDefault()
  // Stash the event so it can be triggered later.
  store.commit('shared/setDeferredPrompt', event)
  console.log(event)
  return false
})
