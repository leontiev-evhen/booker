<template>
  	<div class="author">
	  	<h3 class="my-4">Edit Author</h3>
		<form @submit.prevent="edit">
			<div class="form-group">
			    <label for="text">Name:</label>
				<p :class="{ 'control': true }"><input v-model="name" v-validate="'required|alpha'" :class="{'input form-control': true, 'is-danger': errors.has('name') }"type="text" name="name" v-default-value="author.name"></p>
				<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
			</div>
			<button type="submit" class="btn btn-primary">Edit</button>
		</form>
	</div>
</template>


<script>

export default {
  	name: 'author',
 	 data() {
		return {
      		author: '',
      		name: ''
		}
  	},
 	methods: {
 		edit: function() {
		
			let config = {
				headers: {
					'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
				}
			}
		
			this.axios.put(this.$parent.$parent.AJAX_URL + '/book/client/api/authors/' + this.$route.params.id, {
					name: (this.name) ? this.name : this.author.name
				}, config)  
				.then((response) => {

					if (response.status == 200) {
						if (!response.data.success) {
							console.log(response.data.message)
						} else {
							location.href = '/admin/authors'
						}
					} else {
						console.log(response.data.message)
					}
				})
			
 		}
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/authors/' + this.$route.params.id).then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.author = response.data.data
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
