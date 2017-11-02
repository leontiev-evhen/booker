// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

import axios from 'axios'
import VueAxios from 'vue-axios'

import VeeValidate from 'vee-validate'
import VueDefaultValue from 'vue-default-value'

import Vuex from 'vuex'
Vue.use(Vuex)
import store from './libs/store'

Vue.use(VeeValidate)
Vue.use(VueAxios, axios)
Vue.use(VueDefaultValue)

Vue.config.productionTip = false


/* eslint-disable no-new */


new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App },
  store
})
