import Vue from 'vue'
import Router from 'vue-router'

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
			},
			{
				path: '/admin/user/create',
				name: 'AdminUserCreate',
				component: AdminUserCreate,
			},
			{
				path: '/admin/user/edit/:id',
				name: 'AdminUserEdit',
				component: AdminUserEdit,
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


