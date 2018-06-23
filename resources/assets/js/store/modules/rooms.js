import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  rooms: null,
  newMessagesArrived: []
}

// getters
export const getters = {
  rooms: state => state.rooms,

  room: state => (id) => {
    if (state.rooms) {
      return state.rooms.find(room => room.id === parseInt(id))
    }
  },

  latestRoom: state => {
    if (state.rooms) {
      let roomsSorted = state.rooms.sort((a, b) => a.updated_at < b.updated_at)
      return roomsSorted[0]
    }
  }
}

// mutations
export const mutations = {

  [types.FETCH_ROOMS_SUCCESS] (state, { rooms }) {
    state.rooms = rooms
  },

  [types.FETCH_ROOMS_FAILURE] (state) {
    state.rooms = null
  },

  cleanUpRooms (state) {
    state.rooms = state.rooms.filter(el => el.id !== 0)
  },

  clearRoomFromNewMessagesArrived (state, payload) {
    state.newMessagesArrived = state.newMessagesArrived
      .filter(el => el.room_id !== payload)
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
