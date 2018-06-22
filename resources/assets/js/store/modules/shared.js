
// state
export const state = {
  dialog: '',
  action: null,
  deferredPrompt: 0,
  newRoomMembers: [],
  frontendTimestamp: null,
  latestFrontendVersion: null,
  chatroomName: 'shnatroom'
}

// getters
export const getters = {
  dialog: state => state.dialog,
  action: state => state.action,
  chatroomName: state => state.chatroomName,
  newRoomMembers: state => state.newRoomMembers,
  deferredPrompt: state => state.deferredPrompt,
  frontendTimestamp: state => state.frontendTimestamp,
  latestFrontendVersion: state => state.latestFrontendVersion
}

// mutations
export const mutations = {
  setDeferredPrompt (state, payload) {
    state.deferredPrompt = payload
  },
  setDialog (state, payload) {
    state.dialog = payload
  },
  setAction (state, payload) {
    state.action = payload
  },
  setChatroomName (state, payload) {
    state.chatroomName = payload
  },
  setNewRoomMembers (state, payload) {
    state.newRoomMembers = payload
  },
  addRoom (state, payload) {
    state.action = {
      type: 'roomAdded',
      what: payload
    }
  },
  setFrontendTimestamp (state, payload) {
    state.frontendTimestamp = payload
  },
  setLatestFrontendVersion (state, payload) {
    state.latestFrontendVersion = payload
  }
}

// actions
export const actions = {
  getLatestFrontendVersion ({
    commit,
    dispatch,
    rootState
  }) {
    window.axios
      .get('/api/getlatestfrontendversion')
      .then(response => {
        if (!response.data) {
          window.console.warn(response)
        }
        if (response.data.frontendVersion) {
          commit('setLatestFrontendVersion', response.data.frontendVersion)
          // setTimeout to repeat this
          setTimeout(() => {
            dispatch('getLatestFrontendVersion')
          }, 60000)
        }
      })
      .catch(err => rootState.commit('axiosError', err))
  }
}
