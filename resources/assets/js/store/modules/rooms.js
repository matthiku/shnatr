import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  rooms: null
}

// getters
export const getters = {
  rooms: state => state.rooms
}

// mutations
export const mutations = {

  [types.FETCH_ROOMS_SUCCESS] (state, { rooms }) {
    state.rooms = rooms
  },

  [types.FETCH_ROOMS_FAILURE] (state) {
    state.rooms = null
  }

}

// actions
export const actions = {
  /* jshint ignore:start */

  async fetchRooms ({ commit }) {
    try {
      const { data } = await axios.get('/api/rooms')

      commit(types.FETCH_ROOMS_SUCCESS, { rooms: data })
    } catch (e) {
      commit(types.FETCH_ROOMS_FAILURE)
    }
  }

  /* jshint ignore:end */
}
