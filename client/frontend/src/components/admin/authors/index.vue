<template>
  	<div class="author">
  		
	  	<h3 class="my-4">Authors</h3>

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
					<tr v-for="(author, key) in authors">
					  	<td>{{key+1}}</td>
					  	<td>{{author.name}}</td>
					  	<td>{{author.create_at}}</td>
					  	<td>
						  	<a :href="'/admin/author/edit/' + author.id" class="btn btn-warning">
						  		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						  	</a>
							<button type="button" class="btn btn-danger" @click="remove(author.id)">
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
  	name: 'author',
 	 data() {
		return {
      		authors: '',
			error: ''
		}
  	},
 	methods: {
 		remove: function(id) {
 			let answer = confirm('Are you sure?')
 			if (answer) {
 				this.axios.delete(this.$parent.$parent.AJAX_URL + '/book/client/api/authors/' + id).then((response) => {

			        if (response.status == 200) {
			            if (response.data.success) {
			              	location.reload()
			            } else {
			              	this.error = response.data.message
			            }
			        } else {
			            console.log(response.data.message)
			        }
		    	})
 			}
 		}
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/authors').then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.authors = response.data.data
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