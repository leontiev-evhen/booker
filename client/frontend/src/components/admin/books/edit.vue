<template>
  	<div class="edit">
	  	<h3 class="my-4">Edit Book</h3>
	  	<tabs :options="{ useUrlFragment: false }">
	        <tab name="Book info">
	            <form @submit.prevent="edit">
					<div class="form-group">
					    <label for="text">Name:</label>
						<p :class="{ 'control': true }"><input v-model="name" v-validate="'required|alpha'" :class="{'input form-control': true, 'is-danger': errors.has('name') }"type="text" name="name" v-default-value="book.name"></p>
						<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
					</div>
					<div class="form-group">
					    <label for="text">Description:</label>
					    <textarea  class="form-control" v-default-value="book.description" v-model="description"></textarea>
					</div>
					<div class="form-group">
					    <label for="text">Price:</label>
						<p :class="{ 'control': true }"><input v-model="price" v-validate="'required|alpha'" :class="{'input form-control': true, 'is-danger': errors.has('price') } "type="text" name="price" v-default-value="book.price"></p>
					</div>
					<div class="form-group">
					    <label for="text">Discaunt:</label>
					    <input type="text" class="form-control" v-default-value="book.discaunt" v-model="discaunt">
					</div>
					<button type="submit" class="btn btn-primary">Edit</button>
				</form>
	        </tab>
	        <tab name="Choose author">
	            <div class="book_author">
					<p class="success">{{success}}</p>
					<multiselect 
					    v-model="info_author" 
					    :options="authors"
					    :multiple="true"
					    track-by="name"
					    :custom-label="customLabel"
					    >
					</multiselect>
					<button @click="saveAuthor" class="btn btn-default mt20">Save</button>
				</div>
	        </tab>

	        <tab name="Choose genre">
	            <div class="book_genre">
					<p class="success">{{success}}</p>
					<multiselect 
					    v-model="info_genre" 
					    :options="genres"
					    :multiple="true"
					    track-by="name"
					    :custom-label="customLabel"
					    >
					</multiselect>
					<button @click="saveGenre" class="btn btn-default mt20">Save</button>
				</div>
	        </tab>
	     	
    	</tabs>
		

	</div>
</template>


<script>
import Multiselect from 'vue-multiselect'
import {Tabs, Tab} from 'vue-tabs-component';

export default {
	
  	name: 'edit',
 	 data() {
		return {
			config: {
		  		headers: {
		    		'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
		  		}
			},
      		book: '',
      		name: '',
      		description: '',
      		price: 0,
      		discaunt: 0,
      		authors: [],
      		genres: [],
      		info_author: [],
      		info_genre: [],
      		success: ''
		}
  	},
 	methods: {

 		saveAuthor: function () {
			
			this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/books/' + this.$route.params.id + '/author', {
					ids: this.info_author
				}, this.config)  
		    .then((response) => {

		        if (response.status == 200) {
		            if (!response.data.success) {
		              	console.log(response.data.message)
		            } else {
		            	this.success = ''
		         		this.success = response.data.message
		            }
		        } else {
		            console.log(response.data.message)
		        }
	    	})
 		},

 		saveGenre: function () {
 			this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/books/' + this.$route.params.id + '/genre', {
					ids: this.info_genre
				}, this.config)  
		    .then((response) => {

		        if (response.status == 200) {
		            if (!response.data.success) {
		              	console.log(response.data.message)
		            } else {
		            	this.success = ''
		         		this.success = response.data.message
		            }
		        } else {
		            console.log(response.data.message)
		        }
	    	})
 		},

 		customLabel: function (option) {
      		return option.name
    	},

 		edit: function() {
			
			this.axios.put(this.$parent.$parent.AJAX_URL + '/book/client/api/books/' + this.$route.params.id, {
					name: (this.name) ? this.name : this.book.name,
					description: (this.description) ? this.description : this.book.description,
					price: (this.price) ? +this.price : +this.book.price,
					discaunt: (this.discaunt) ? +this.discaunt : +this.book.discaunt,
				}, this.config)  
				.then((response) => {

					if (response.status == 200) {
						if (!response.data.success) {
							console.log(response.data.message)
						} else {
							location.href = '#/admin/books'
						}
					} else {
						console.log(response.data.message)
					}
				})
			
 		}
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/books/' + this.$route.params.id).then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
            	/*get books*/
              	this.book = response.data.data

              	/*get authors*/
                for (let key in response.data.data.authors) {
					let obj = {
						id: response.data.data.authors[key].id,
						name: response.data.data.authors[key].name
					}
					this.info_author.push(obj)
				}

				/*get genres*/
                for (let key in response.data.data.genres) {
					let obj = {
						id: response.data.data.genres[key].id,
						name: response.data.data.genres[key].name
					}
					this.info_genre.push(obj)
				}

            } else {
              	console.log(response.data.message)
            }
        } else {
            console.log(response.data.message)
        }
    })

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/authors').then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	//this.authors = response.data.data
         
              	for (let key in response.data.data) {
						let obj = {
							id: response.data.data[key].id,
							name: response.data.data[key].name
						}
						this.authors.push(obj)
				}


            } else {
              	console.log(response.data.message)
            }
        } else {
            console.log(response.data.message)
        }
    })

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/genres').then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	for (let key in response.data.data) {
						let obj = {
							id: response.data.data[key].id,
							name: response.data.data[key].name
						}
						this.genres.push(obj)
				}
            } else {
              	console.log(response.data.message)
            }
        } else {
            console.log(response.data.message)
        }
    })

  	},
  	components: {
  		Multiselect, Tabs, Tab
  	},
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>


</style>
