<template>
  	<div class="users">
  		
	  	<h3 class="my-4">
	  		<i class="fa fa-users" aria-hidden="true"></i>
	  		 Users
	  	</h3>
	  	<div>
	  		<a :href="$parent.$parent.BASE_URL + '/#/user/create'" class="btn btn-primary">Add</a>
	  	</div>
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
					<tr v-for="(user, key) in users" :data-id = "user.id">
					  	<td>{{key+1}}</td>
					  	<td><a :href="'mailto:' + user.email">{{user.name}}</a></td>
					  	<td>{{user.create_at}}</td>
					  	<td>
					  		<i v-if="user.id_role == 1" class="fa fa-user" aria-hidden="true"></i>
					  	</td>
					  	<td>
						  	<a :href="$parent.$parent.BASE_URL + '/#/user/edit/' + user.id" class="btn btn-warning">
						  		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						  	</a>
						  	<button v-if="user.id_role != 1" type="button" class="btn btn-danger" @click="remove(user.id)">
								<i class="fa fa-trash-o" aria-hidden="true"></i>
							</button>
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
  	},
  	methods: {
 		remove: function(id) {

			let self = this;
 			this.$swal({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, delete it!'
			}).then(function () {
			  self.axios.delete(self.$parent.$parent.AJAX_URL + '/booker/client/api/users/id/' + id).then((response) => {

			        if (response.status == 200) {
			            if (response.data.success) {
			            	self.$swal(    
			            		'Deleted!',
							    'User has been deleted.',
							    'success'
							);
			              	$('tr[data-id=' + id + ']').remove();
			            } else {
			              	self.error = response.data.message
			              	self.$swal(    
			            		'Error!',
							    response.data.message,
							    'error'
							);
			            }
			        } else {
			            console.log(response.data.message)
			        }
		    	})
			}, function (dismiss) {
			
			});

 		}
  	},
}
</script>


<style>

</style>

