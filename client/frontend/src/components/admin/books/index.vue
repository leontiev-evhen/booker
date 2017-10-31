<template>
  	<div class="book">
  		
	  	<h3 class="my-4">Books</h3>

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
					<tr v-for="(book, key) in books">
					  	<td>{{key+1}}</td>
					  	<td>{{book.name}}</td>
					  	<td>{{book.create_at}}</td>
					  	<td>
						  	<a :href="'/admin/book/edit/' + book.id" class="btn btn-warning">
						  		<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						  	</a>
							<button type="button" class="btn btn-danger" @click="remove(book.id)">
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
      		books: '',
			error: ''
		}
  	},
 	methods: {
 		remove: function(id) {
 			let answer = confirm('Are you sure?')
 			if (answer) {
 				this.axios.delete(this.$parent.$parent.AJAX_URL + '/book/client/api/books/' + id).then((response) => {

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

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/books').then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.books = response.data.data
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
