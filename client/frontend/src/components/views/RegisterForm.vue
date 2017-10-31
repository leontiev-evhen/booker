<template>
	<div class="register_form col-md-6">
		<h3 class="my-4">Registration</h3>
		<p class="success_m" v-if="success">{{success}}</p>
		<form v-else @submit.prevent="validForm">
			<p v-if="error" class="is-danger">{{error}}</p>
			<p :class="{ 'control': true }"><input v-model="name" v-validate="'required|alpha'" :class="{'input form-control': true, 'is-danger': errors.has('name') }"type="text" name="name" placeholder="Name"></p>
			<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>

			<p :class="{ 'control': true }"><input v-model="email" v-validate="'required|email'" :class="{'input form-control': true, 'is-danger': errors.has('email') }" type="text" name="email" placeholder="Email"></p>
			<span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>

			<p :class="{ 'control': true }"><input v-model="password" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('password') }"type="password" name="password" placeholder="Password"></p>
			<span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>

			<p :class="{ 'control': true }"><input v-model="repeat_password" v-validate="'required|confirmed:password'" :class="{'input form-control': true, 'is-danger': errors.has('repeat_password') }"type="password" name="repeat_password" placeholder="Repeat password" data-vv-as="password"></p>
			<span v-show="errors.has('repeat_password')" class="help is-danger">{{ errors.first('repeat_password') }}</span>
			
			<p><button type="submit" class="btn btn-primary">Submit</button></p>
		</form>
	</div>
</template>

<script>
	export default {
  	name: 'PreOrderForm',
  	data() {
  		return {
  			name: '',
			email: '',
			password: '',
			repeat_password: '',
			error: '',
			success: ''
  		}
  	},
  	methods: {
  		validForm: function() {
  			var self = this
			this.$validator.validateAll().then((result) => {
		        if (result) {
		        	let config = {
					  headers: {
					    'Content-Type' : 'application/x-www-form-urlencoded'
					  }
					}
	    
				    this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/auth', {
				    	name: this.name,
				    	email: this.email,
				    	password: btoa(this.password)
				    }, config)  
				    .then(function (response) {
				    	if (!response.data.success) {
				    		self.error = response.data.message
				    	} else {
				    		self.success = response.data.message
				    	}
				    	
					})
					.catch(function (error) {
					    console.log(error);
					});
			    	
		        }
     		});
		}
  	},
  	/*created() {
  		if (localStorage.getItem("profile")) {
  			location.href = '/';
  		}
  	}*/
}
</script>
<style>

</style>

