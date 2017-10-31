import Vue from 'vue'
import Router from 'vue-router'

import PageNotFound from '@/components/PageNotFound'
import Home from '@/components/Home'


Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '*',
      name: 'PageNotFound ',
      component: PageNotFound 
    },
  ]
})
