import Vue from 'vue'
import Router from 'vue-router'
import store from '../libs/store'

import PageNotFound from '@/components/PageNotFound'
import Home from '@/components/Home'
import Login from '@/components/Login'
import Layout from '@/components/Layout'


/*admin*/
import Admin from '@/components/admin/dashboard/index'

import AdminUsers from '@/components/admin/users/index'
import AdminUserEdit from '@/components/admin/users/edit'
import AdminUserCreate from '@/components/admin/users/create'

import AdminEventEdit from '@/components/admin/events/edit'
import AdminEventCreate from '@/components/admin/events/create'


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
				path: '/admin',
				name: 'Admin',
				component: Admin,
			},
			{
				path: '/admin/users',
				name: 'AdminUsers',
				component: AdminUsers,
            beforeEnter: is_admin
			},
			{
				path: '/admin/user/create',
				name: 'AdminUserCreate',
				component: AdminUserCreate,
            beforeEnter: is_admin
			},
			{
				path: '/admin/user/edit/:id',
				name: 'AdminUserEdit',
				component: AdminUserEdit,
            beforeEnter: is_admin
			},
			{
				path: '/admin/event/create',
				name: 'AdminEventCreate',
				component: AdminEventCreate,
			},
			{
				path: '/admin/event/edit/:id',
				name: 'AdminEventEdit',
				component: AdminEventEdit,
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


