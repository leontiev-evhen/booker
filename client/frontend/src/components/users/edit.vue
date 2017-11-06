<template>
  	<div class="edit">
	  	<h3 class="my-4"><i class="fa fa-user" aria-hidden="true"></i> Edit user</h3>
	  	<p class="is-danger">{{error}}</p>
		<form @submit.prevent="edit">
			<div class="form-group">
			    <label for="text">Name:</label>
				<p :class="{ 'control': true }"><input v-model="name" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('name') }" type="text" name="name" v-default-value="user.name"></p>
				<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Email:</label>
				<p :class="{ 'control': true }"><input v-model="email" v-validate="'required|alpha'" :class="{'input form-control': true, 'is-danger': errors.has('email') }" type="text" name="email" v-default-value="user.email"></p>
				<span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
			</div>
			<div class="form-group">
				<label for="text">Role:</label><br>
				<label for="text">Admin:</label>
				<input name="id_role" type="radio" :value="1" @click="isStatus($event)" :checked="user.id_role == 1">
				<label for="text">User:</label>
				<input name="id_role" type="radio" :value="2" @click="isStatus($event)" :checked="user.id_role == 2">
			</div>
			
			<button type="submit" class="btn btn-primary">Edit</button>
		</form>
	</div>
</template>


<script>

export default {
  	name: 'edit',
 	 data() {
		return {
      		user: '',
      		name: '',
			email: '',
			id_role: 0,
			error: ''
		}
  	},
 	methods: {

 		isStatus: function(event) {
 			this.id_role = event.target.value
 		},
 		edit: function() {
			let self = this;
			let config = {
				headers: {
					'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
				}
			}
		
			this.axios.put(this.$parent.$parent.AJAX_URL + '/booker/client/api/users/id/' + this.$route.params.id, {
					name: (this.name) ? this.name : this.user.name,
					email: (this.email) ? this.email : this.user.email,
					id_role: (this.id_role) ? +this.id_role : +this.user.id_role,
				}, config)  
				.then((response) => {

					if (response.status == 200) {
						if (!response.data.success) {
								self.$swal(
									'Warning!',
    								response.data.message,
    								'warning'
    							);
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
  	},
    created() {
   
    this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/users/id/' + this.$route.params.id).then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.user = response.data.data
            } else {
              	console.log(response.data.message)
            }
        } else {
            console.log(response.data.message)
        }
    })

    
  	},
}
</script>


<style>

</style>
