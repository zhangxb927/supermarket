// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

import $ from 'jquery';

import echarts from 'echarts'
Vue.prototype.$echarts = echarts

import './common/reset.css'
import './common/icon-font/iconfont.css'

import commonJS from './common/common.js';
Vue.prototype.commonJS = commonJS;

Vue.config.productionTip = false

Vue.prototype.$ajax = function (url, type, data, success) {
  $.ajax({
    url: url,
    type: type,
    data: data,
    success: function (data, textStatus, jqXHR) {
      if (success) {
        success(data, textStatus, jqXHR)
      }
    },
    error: function(){
      this.$message({
        type: 'error',
        message: '登录失败'
      })
    }
  });
}

router.beforeEach((to, from, next) => {
  if (to.meta.requireAuth) {
    var token = store.state.token;
    if (token) {
      next();
    } else {
      next({
        path: "/login"
      });
    };
  } else {
    next();
  }
});

new Vue({
  el: '#app',
  store,
  router,
  components: { App },
  template: '<App/>'
})
