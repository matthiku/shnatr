import store from '~/store'

/* jshint ignore:start */

export default async (to, from, next) => {
  if (!store.getters['auth/check']) {
    next({ name: 'login' })
  } else {
    if (!store.getters['auth/users']) {
      try {
        await store.dispatch('auth/fetchUsers')
      } catch (e) {}
    }
    next()
  }
}
