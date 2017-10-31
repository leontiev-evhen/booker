<template>
  	<div class="genre">
  		
	  	<h3 class="my-4">Genres</h3>

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
					<tr v-for="(genre, key) in genres">
					  	<td>{{key+1}}</td>
					  	<td>{{genre.name}}</td>
					  	<td>{{genre.create_at}}</td>
					  	<td>
						  	<a :href="'/admin/genre/edit/' + genre.id" class="btn btn-warning">
						  		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						  	</a>
							<button type="button" class="btn btn-danger" @click="remove(genre.id)">
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
  	name: 'index',
 	 data() {
		return {
      		genres: '',
      		error: ''
		}
  	},
 	methods: {
 		remove: function(id) {
 			let answer = confirm('Are you sure?')
 			if (answer) {
 				this.axios.delete(this.$parent.$parent.AJAX_URL + '/book/client/api/genres/' + id).then((response) => {

			        if (response.status == 200) {
			            if (response.data.success) {
			            	location.reload()
			            } else {
			              	this.error = response.data.message
			            }
			        } else {
			        	this.error = response.data.message
			        }
		    	})
 			}
 		}
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/genres').then((response) => {

        if (response.status == 200) {
            if (response.data.success) {
              	this.genres = response.data.data
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
