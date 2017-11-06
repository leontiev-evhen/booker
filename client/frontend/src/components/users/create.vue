<template>
  	<div class="create col-md-6">
	  	<h3 class="my-4"><i class="fa fa-user-plus" aria-hidden="true"></i> Create User</h3>
		<form @submit.prevent="create">
			<p v-if="error" class="is-danger">{{error}}</p>
			<div class="form-group">
			    <label for="text">Name:</label>
			    <p :class="{ 'control': true }"><input v-model="name" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('name') }"type="text" name="name"></p>
				<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Email:</label>
				<p :class="{ 'control': true }"><input v-model="email" v-validate="'required|email'" :class="{'input form-control': true, 'is-danger': errors.has('email') }"type="text" name="email"></p>
				<span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Password:</label>
				<p :class="{ 'control': true }"><input v-model="password" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('password') }" type="password" name="password"></p>
				<span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
			</div>
			<div class="form-group">
				<label for="text">Repeat password:</label>
				<p :class="{ 'control': true }"><input v-model="repeat_password" v-validate="'required|confirmed:password'" :class="{'input form-control': true, 'is-danger': errors.has('repeat_password') }"type="password" name="repeat_password" data-vv-as="password"></p>
				<span v-show="errors.has('repeat_password')" class="help is-danger">{{ errors.first('repeat_password') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Role:</label>
			    <select v-model="id_role" class="form-control">
			    	<option value="1">Admin</option>
			    	<option value="2">User</option>
			    </select>
			</div>
			<button type="submit" class="btn btn-primary">Create</button>
		</form>
	</div>
</template>


<script>

export default {
  	name: 'create',
 	 data() {
		return {
      		name: '',
			email: '',
			password: '',
			repeat_password: '',
			id_role: '',
			error: ''
		}
  	},
 	methods: {
 		create: function() {
 			let self = this
			this.$validator.validateAll().then((result) => {
		        if (result) {
					let config = {
						headers: {
							'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
						}
					}
				
					this.axios.post(this.$parent.$parent.AJAX_URL + '/booker/client/api/users', {
							name: this.name,
							email: this.email,
							password: btoa(this.password),
							id_role: (this.id_role) ? +this.id_role : 2,
						}, config)  
						.then((response) => {

							if (response.status == 201) {
								if (!response.data.success) {
									self.error = response.data.message
								} else {
									self.$swal(
										'Success!',
    									response.data.message,
    									'success'
    								);
									self.$router.push('/users');
								}
							} else {
								console.log(response.data.message)
							}
						})
				}
     		});
 		}
  	},
}
</script>


<style>

</style>
