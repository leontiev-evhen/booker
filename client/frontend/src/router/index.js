import Vue from 'vue'
import Router from 'vue-router'

import PageNotFound from '@/components/PageNotFound'
import Home from '@/components/Home'
import Login from '@/components/Login'
import Calendar from '@/components/Calendar'
import Layout from '@/components/Layout'


Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Layout',
      component: Layout,
      children: [
         {
         path: '',
         name: 'Calendar',
         component: Calendar,
      }
      ]
    },
	{
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '*',
      name: 'PageNotFound ',
      component: PageNotFound 
    },
  ]
});


