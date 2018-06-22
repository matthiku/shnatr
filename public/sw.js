importScripts("/precache-manifest.4d4e01190a349b0195213507044e8d67.js", "https://storage.googleapis.com/workbox-cdn/releases/3.2.0/workbox-sw.js");

/* global workbox */
/* eslint no-undef: "error" */

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
//   workbox.strategies.CacheFirst({
//     cacheName: 'image-cache'
//   })
// )

/**
 * Dynamic caching of the Main Pages
 *
 * try network first, then cache
 */
const mainPaths = [
  '/',
  '/home',
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

