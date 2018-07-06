importScripts("/precache-manifest.5824966b1e01da61eb66c404199b7828.js", "https://storage.googleapis.com/workbox-cdn/releases/3.2.0/workbox-sw.js");

/* global workbox */
/* eslint no-undef: "error" */

import Echo from 'laravel-echo'

/**
 * CACHING static files
 *
 * The service worker file should reference the self.__precacheManifest variable
 * to obtain a list of ManifestEntrys obtained as part of the compilation
 */
workbox.precaching.precacheAndRoute(self.__precacheManifest || [])

// fonts etc should be cached and fetched from cache first, then validated from the network
workbox.routing.registerRoute(
  /.*cdn\.polyfill\.io.*$/,
  workbox.strategies.staleWhileRevalidate({
    cacheName: 'staleWithRevalid'
  })
)

// third-party images (e.g. avatars)
// see https://developers.google.com/web/tools/workbox/modules/workbox-cacheable-response
workbox.routing.registerRoute(
  new RegExp('^https://www.gravatar.com/avatar/'),
  workbox.strategies.cacheFirst({
    cacheName: 'image-cache',
    plugins: [
      new workbox.cacheableResponse.Plugin({
        statuses: [0, 200]
      }),
      new workbox.expiration.Plugin({
        maxEntries: 20
      })
    ]
  })
)
// workbox.routing.registerRoute(
//   /.*\.(?:png|jpg|jpeg|svg|gif)/g,
//   workbox.strategies.cacheFirst({
//     cacheName: 'image-cache'
//   })
// )
workbox.routing.registerRoute(
  new RegExp('/static/'),
  workbox.strategies.cacheFirst({
    cacheName: 'image-cache'
  })
)

/**
 * Dynamic caching of the Main Pages
 *
 * try network first, then cache
 */
const mainPaths = [
  '/',
  '/home',
  '/chat',
  '/chat/users',
  '/chat/rooms',
  '/login',
  '/register'
]
const mainURLs = ({url, event}) => {
  // Return true if the route should match
  if (mainPaths.indexOf(url.pathname) > -1) return true
  return false
}
workbox.routing.registerRoute(
  mainURLs,
  workbox.strategies.networkFirst({
    cacheName: 'semi-static'
  })
)

/**
 * API GET requests cached with networkFirst
 */
workbox.routing.registerRoute(
  new RegExp('/api/*'),
  workbox.strategies.networkFirst({
    cacheName: 'api-get-requests'
  })
)

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

