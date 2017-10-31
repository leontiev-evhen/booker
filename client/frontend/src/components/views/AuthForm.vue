<template>
	<div>
		<p v-if="error" class="auth-danger">{{error}}</p>
		<div class="auth_form" v-if="!access">
			<form @submit.prevent="validForm" class="navbar-form navbar-left">
				<div class="form-group">
					<p :class="{ 'control': true }"><input v-model="email" v-validate="'required|email'" :class="{'input form-control': true, 'is-danger': errors.has('email') }" type="text" name="email" placeholder="Email"></p>
				</div>
				<div class="form-group">
					<p :class="{ 'control': true }"><input v-model="password" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('password') }"type="password" name="password" placeholder="Password"></p>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				<a class="register_link" href="/register">Register</a>
			</form>
		</div>
		<div class="user_block" v-else>
			<i class="fa fa-user-times" aria-hidden="true"></i>
			

			<router-link :to="{ name: 'layout.User'}">{{this.$parent.$parent.$parent.user.name}}</router-link>

			<p class="logout"><a href="#" @click="logout">Logout</a></p>
		</div>
	</div>
</template>

<script>
	export default {
  	name: 'AuthForm',
  	data() {
  		return {
			email: '',
			password: '',
			error: '',
			access: false
  		}
  	},
  	methods: {
  		validForm: function() {
  			var self = this
			this.$validator.validateAll().then((result) => {
			
		        if (result) {
		        	let config = {
					  headers: {
					    'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8',
					  }
					}
	    
				    this.axios.put(this.$parent.$parent.$parent.AJAX_URL + '/book/client/api/auth', {
				    	email: this.email,
				    	password: btoa(this.password)
				    }, config)  
				    .then((response) => {

			          if (response.status == 200) {
			            if (!response.data.success) {
			              	self.error = response.data.message
			            } else {
			              	localStorage.setItem('profile', JSON.stringify(response.data.data));
			              	self.access = true
			              	this.$emit('login')
			            }
			          } else {
			            	console.log(response.data.message)
			          }
			    	})
		        }
     		});
		},
		logout: function () {
			this.email = ''
			this.password = ''
			this.access = false
			this.$emit('logout')
		}
  	},
  	created() {
  		if (this.$parent.$parent.$parent.user) {
  			this.access = true
  		}
  		
  	}
}
</script>
<style>

</style>
