import Vue from 'vue'
import Router from 'vue-router'
import store from '../libs/store'

import PageNotFound from '@/components/PageNotFound'
import Home from '@/components/views/Home'
import Login from '@/components/views/Login'
import Layout from '@/components/Layout'


/*user*/
import Users from '@/components/users/index'
import UserEdit from '@/components/users/edit'
import UserCreate from '@/components/users/create'

import EventEdit from '@/components/events/edit'
import EventCreate from '@/components/events/create'


Vue.use(Router)

function is_admin (to, from, next) {
   if (store.is_admin) {
      next();
   } else {
      next('/');
   }
}

export default new Router({
  //mode: 'history',
  routes: [
    {
		path: '/',
		name: 'Layout',
		component: Layout,
		children: [
			{
				path: '',
				name: 'Home',
				component: Home,
			},
			{
				path: '/users',
				name: 'Users',
				component: Users,
            	beforeEnter: is_admin
			},
			{
				path: '/user/create',
				name: 'UserCreate',
				component: UserCreate,
            	beforeEnter: is_admin
			},
			{
				path: '/user/edit/:id',
				name: 'UserEdit',
				component: UserEdit,
            	beforeEnter: is_admin
			},
			{
				path: '/event/create/room/:id',
				name: 'EventCreate',
				component: EventCreate,
			},
			{
				path: '/event/edit/:id',
				name: 'EventEdit',
				component: EventEdit,
			},
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


