<template>
  	<div class="users">
  		
	  	<h3 class="my-4"><i class="fa fa-users" aria-hidden="true"></i> Users</h3>

  		<div class="list-group">
			<p class="is-danger">{{error}}</p>
  			<table class="table table-hover">
				<thead>
					<tr>
						<th>№</th>
					  	<th>Name</th>
					  	<th>Create</th>
					  	<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(user, key) in users">
					  	<td>{{key+1}}</td>
					  	<td>{{customer.name}}</td>
					  	<td>{{customer.create_at}}</td>
					  	<td>
						  	<a :href="'/user/edit/' + user.id" class="btn btn-warning">
						  		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						  	</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>


<script>

export default {
  	name: 'users',
 	 data() {
		return {
      		users: '',
			error: ''
		}
  	},
    created() {

		this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/users').then((response) => {

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
  	},
}
</script>


<style>

</style>

