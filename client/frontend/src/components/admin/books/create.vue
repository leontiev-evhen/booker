<template>
  	<div class="create">
	  	<h3 class="my-4">Create Book</h3>
		<form @submit.prevent="create">
			<div class="form-group">
			    <label for="text">Name:</label>
			    <p :class="{ 'control': true }"><input v-model="name" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('name') }"type="text" name="name"></p>
				<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Description:</label>
			    <textarea  class="form-control" v-model="description"></textarea>
			</div>
			<div class="form-group">
			    <label for="text">Price:</label>
				<p :class="{ 'control': true }"><input v-model="price" v-validate="'required|numeric'" :class="{'input form-control': true, 'is-danger': errors.has('price') }"type="text" name="price"></p>
				<span v-show="errors.has('price')" class="help is-danger">{{ errors.first('price') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Discaunt:</label>
			    <input type="text" class="form-control" v-model="discaunt">
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
			description: '',
			price: '',
			discaunt: ''
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
				
					this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/books', {
							name: this.name,
							description: this.description,
							price: +this.price,
							discaunt: +this.discaunt,
						}, config)  
						.then((response) => {

							if (response.status == 200) {
								if (!response.data.success) {
									console.log(response.data.message)
								} else {
									location.href = '/admin/books'
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
