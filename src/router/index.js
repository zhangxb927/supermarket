import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/login'
import Main from '@/components/main'
import Home from '@/components/home'
import Addmanager from '@/components/addmanager'
import Managerlist from '@/components/managerlist'
import Addmarket from '@/components/addmarket'
import Marketlist from '@/components/marketlist'
import Addgoods from '@/components/addgoods'
import Goodslist from '@/components/goodslist'
import Goodscount from '@/components/goodscount'
import Salelist from '@/components/salelist'
import Saleanalysis from '@/components/saleanalysis'
import Userlist from '@/components/userlist'
import Usersale from '@/components/usersale'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [{
    path: '/',
    name: 'main',
    component: Main,
    meta: {
      requireAuth: true
    },
    children: [{
      path: 'home',
      name: 'home',
      component: Home,
      meta: {
        requireAuth: true
      }
    },{
      path: 'addmanager',
      name: 'addmanager',
      component: Addmanager,
      meta: {
        requireAuth: true
      }
    },{
      path: 'managerlist',
      name: 'addmanagerlist',
      component: Managerlist,
      meta: {
        requireAuth: true
      }
    },{
      path: 'addmarket',
      name: 'addmarket',
      component: Addmarket,
      meta: {
        requireAuth: true
      }
    },{
      path: 'marketlist',
      name: 'marketlist',
      component: Marketlist,
      meta: {
        requireAuth: true
      }
    },{
      path: 'addgoods',
      name: 'addgoods',
      component: Addgoods,
      meta: {
        requireAuth: true
      }
    },{
      path: 'goodslist',
      name: 'goodslist',
      component: Goodslist,
      meta: {
        requireAuth: true
      }
    },{
      path: 'goodscount',
      name: 'goodscount',
      component: Goodscount,
      meta: {
        requireAuth: true
      }
    },{
      path: 'salelist',
      name: 'salelist',
      component: Salelist,
      meta: {
        requireAuth: true
      }
    },{
      path: 'saleanalysis',
      name: 'saleanalysis',
      component: Saleanalysis,
      meta: {
        requireAuth: true
      }
    },{
      path: 'userlist',
      name: 'userlist',
      component: Userlist,
      meta: {
        requireAuth: true
      }
    },{
      path: 'usersale',
      name: 'usersale',
      component: Usersale,
      meta: {
        requireAuth: true
      }
    }]
  },{
    path: '/login',
    name: 'login',
    component: Login
  }]
})
