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
  },

})

export default store
