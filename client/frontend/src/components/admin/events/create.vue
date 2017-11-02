<template>
  	<div class="create col-md-6">
	  	<h3 class="my-4"><i class="fa fa-list-alt" aria-hidden="true"></i> Create Event</h3>
		<form @submit.prevent="create">
			<p v-if="error" class="is-danger">{{error}}</p>
			<div class="form-group">
				<label for="text">Booked for:</label>
				<select v-model="id_role" class="form-control">
			    	<option v-for="user in users" :value="user.id">{{user.name}}</option>
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
      		users: '',
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
							discaunt: (this.discaunt) ? +this.discaunt : 0,
							status: 1,
						}, config)  
						.then((response) => {

							if (response.status == 200) {
								if (!response.data.success) {
									self.error = response.data.message
								} else {
									location.href = '/admin/users'
								}
							} else {
								console.log(response.data.message)
							}
						})
				}
     		});
 		}
  	},
  	created() {
  		this.axios.get(this.$parent.$parent.AJAX_URL + '/booker/client/api/users').then((response) => {

			if (response.status == 200) {
				if (response.data.success) {
					this.users = response.data.data
				} else {
					this.error = response.data.message
				}
			} else {
				console.log(response.data.message)
			}
		})
  	}
}
</script>


<style>

</style>
