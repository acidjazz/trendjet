import Vuex from 'vuex'

const store = () => new Vuex.Store({

  state: {
    user: null,
  },

  mutations: {
    user(state, user) {
      state.user = user || null
    },
  },

  getters: {
    auth (state) {
      return !!state.user
    },
    user (state) {
      return state.user
    },
    isAdmin (state) {
      if (!state.user || state.user.role !== 'admin') {
        return false
      }
      return true
    },
  },

})

export default store
