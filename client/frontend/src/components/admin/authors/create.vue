<template>
  	<div class="author">
	  	<h3 class="my-4">Create Author</h3>
		<form @submit.prevent="create">
			<div class="form-group">
			    <label for="text">Name:</label>
				<p :class="{ 'control': true }"><input v-model="name" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('name') }"type="text" name="name"></p>
				<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
			</div>
			<button type="submit" class="btn btn-primary">Create</button>
		</form>
	</div>
</template>


<script>

export default {
  	name: 'author',
 	 data() {
		return {
      		name: ''
		}
  	},
 	methods: {
 		create: function() {
			this.$validator.validateAll().then((result) => {
		        if (result) {
					let config = {
						headers: {
							'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
						}
					}
				
					this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/authors', {
							name: this.name
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
     		});
 		}
  	}
}
</script>


<style>

</style>
