
// state
export const state = {
  deferredPrompt: 0
}

// getters
export const getters = {
  deferredPrompt: state => state.deferredPrompt
}

// mutations
export const mutations = {
  setDeferredPrompt (state, payload) {
    state.deferredPrompt = payload
  }
}

// actions
export const actions = {
}
