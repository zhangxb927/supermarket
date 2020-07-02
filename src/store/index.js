import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    userinfo: sessionStorage.getItem('state'),
    token: window.sessionStorage.getItem('token')
  },
  getters: {
    getInfo: function(state){
      return state.userinfo;
    }
  },
  mutations: {
    saveInfo(state, info) {
      state.userinfo = info;
      sessionStorage.setItem('state',info);
    },
    addToken(state, token) {
      state.token = token;
      window.sessionStorage.setItem('token', token);
    }
  }
});

export default store;