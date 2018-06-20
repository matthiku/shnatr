import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// state
export const state = {
  user: null,
  users: null,
  verifyEmailSent: false,
  token: Cookies.get('token')
}

// getters
export const getters = {
  user: state => state.user,
  users: state => state.users,
  verifyEmailSent: state => state.verifyEmailSent,
  token: state => state.token,
  check: state => state.user !== null
}

// mutations
export const mutations = {
  [types.SAVE_TOKEN] (state, { token, remember }) {
    state.token = token
    Cookies.set('token', token, { expires: remember ? 365 : null })
  },

  [types.FETCH_USER_SUCCESS] (state, { user }) {
    state.user = user
  },

  [types.FETCH_USER_FAILURE] (state) {
    state.token = null
    Cookies.remove('token')
  },

  [types.FETCH_USERS_SUCCESS] (state, { users }) {
    state.users = users
  },

  [types.FETCH_USERS_FAILURE] (state) {
    state.users = null
  },

  [types.SEND_VERIFY_EMAIL_SUCCESS] (state, { user }) {
    state.verifyEmailSent = true
  },

  [types.SEND_VERIFY_EMAIL_FAILURE] (state) {
    state.verifyEmailSent = false
  },

  [types.LOGOUT] (state) {
    state.user = null
    state.users = null
    state.token = null

    Cookies.remove('token')
  },

  [types.UPDATE_USER] (state, { user }) {
    state.user = user
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  /* jshint ignore:start */
  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      commit(types.FETCH_USER_SUCCESS, { user: data })
    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  async fetchUsers ({ commit }) {
    try {
      const { data } = await axios.get('/api/users')

      commit(types.FETCH_USERS_SUCCESS, { users: data })
    } catch (e) {
      commit(types.FETCH_USERS_FAILURE)
    }
  },

  async sendVerifyEmail ({ commit }) {
    try {
      const { data } = await axios.get('/api/sendverifyemail')
      console.log(data)
      commit(types.SEND_VERIFY_EMAIL_SUCCESS, { user: data })
    } catch (e) {
      commit(types.SEND_VERIFY_EMAIL_FAILURE)
      console.log(e)
    }
  },

  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }

    commit(types.LOGOUT)
  },

  async fetchOauthUrl (ctx, { provider }) {
    const { data } = await axios.post(`/api/oauth/${provider}`)

    return data.url
  }
  /* jshint ignore:end */
}
