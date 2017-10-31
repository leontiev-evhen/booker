<template>
	<div class="auth">
		<h2>Login</h2>
		
		<form  class="login_form" @submit.prevent="validForm">
			<p v-if="error" class="is-danger">{{error}}</p>
			<p :class="{ 'control': true }"><input v-model="email" v-validate="'required|email'" :class="{'input form-control': true, 'is-danger': errors.has('email') }" type="text" name="email" placeholder="Email"></p>
			<span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>

			<p :class="{ 'control': true }"><input v-model="password" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('password') }"type="password" name="password" placeholder="Password"></p>
			<span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
			
			<p><button type="submit" class="btn btn-primary">Login</button></p>
		</form>
	</div>
</template>

<script>
	export default {
  	name: 'login',
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
	    
				    this.axios.put(this.$parent.AJAX_URL + '/book/client/api/auth', {
				    	email: this.email,
				    	password: btoa(this.password),
				    	is_admin: 1
				    }, config)  
				    .then((response) => {

			          if (response.status == 200) {
			            if (!response.data.success) {
			              	self.error = response.data.message
			            } else {
			              	localStorage.setItem('admin', JSON.stringify(response.data.data));
			              	location.href = '/admin';
			            }
			          } else {
			            	console.log(response.data.message)
			          }
			    	})
		        }
     		});
		},
  	},
  	created() {
  		if (localStorage["profile"]) {
  			location.href="/"
  		}
  		
  	}
}
</script>
<style>

</style>
