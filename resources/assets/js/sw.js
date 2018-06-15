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
  /cdn\.polyfill\.io.*$/,
  workbox.strategies.staleWhileRevalidate({
    cacheName: 'google-fonts'
  })
)

// third-party images (avatars)
// see https://developers.google.com/web/tools/workbox/modules/workbox-cacheable-response
workbox.routing.registerRoute(
  /\.(?:png|jpg|jpeg|svg)$/,
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

/**
 * dynamic caching of main pages
 */
const mainPaths = [
  '/',
  '/home',
  '/login',
  '/register'
]
const mainURLs = ({url, event}) => {
  // Return true if the route should match
  console.log(url.pathname)
  if (mainPaths.indexOf(url.pathname) > -1) return true
  return false
}
workbox.routing.registerRoute(
  mainURLs,
  workbox.strategies.networkFirst({
    cacheName: 'semi-static'
  })
)
workbox.routing.registerRoute(
  '/favicon.ico',
  workbox.strategies.cacheFirst({
    cacheName: 'semi-static'
  })
)
