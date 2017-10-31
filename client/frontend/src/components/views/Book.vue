<template>
	<div class="book">
		<h3 class="my-4">{{book.name}}</h3>
		<div class="row">

			<div class="col-md-4">
				<img class="card-img-top" src="http://placehold.it/700x400" alt="">
				<p v-if="price" class="discount"> - <i class="fa fa-eur" aria-hidden="true"></i> {{book.discaunt}}</p>
				<div v-if="this.$parent.$parent.user">
					<button class="btn btn-primary btn-cart mt-20" @click="addCart">Add Cart</button>
					
					<button @click="changeCount(-1)" class="btn btn-default btn-cart mt-20">-</button>
					<span v-model="count" class="count">{{count}}</span>
					<button @click="changeCount(+1)" class="btn btn-default btn-cart mt-20">+</button>
					<p class="success mt-20">{{success}}</p>
				</div>
			</div>
			<div class="col-md-8">
				<h5>Description:</h5>
				{{book.description}}
				<h5 class="mt-20">Price:</h5>
				<span v-if="price">
					<strike><i class="fa fa-eur" aria-hidden="true"></i> {{book.price}}</strike>
					<br>New price: <i class="fa fa-eur" aria-hidden="true"></i> {{price}}
					</span>
				<span v-else>
					<i class="fa fa-eur" aria-hidden="true"></i> {{book.price}}
				</span>
				
				
				<h5 class="mt-20">Authors:</h5>
				<ul>
					<li v-for="author in book.authors">{{author.name}}</li>
				</ul>
				<h5 class="mt-20">Genres:</h5>
				<ul>
					<li v-for="genre in book.genres">{{genre.name}}</li>
				</ul>
			</div>
		</div>
	</div>
</template>
<script>

export default {
  name: 'book',
  data () {
    return {
    	book: '',
    	price: 0,
    	success: '',
		count: 1
    }
  },
  created() {
  	this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/books/' + this.$route.params.id).then((response) => {

          if (response.status == 200) {
            if (response.data.status) {
              this.book = response.data.data

              if (this.book.discaunt != 0) {
              	this.price = this.book.price - this.book.discaunt
              }

            } else {
              console.log(response.data.message)
            }
          } else {
            console.log(response.data.message)
          }
    })

  },
  methods: {
  		addCart: function() {
  			let self = this
		    let config = {
			  headers: {
			    'Content-Type' : 'application/x-www-form-urlencoded'
			  }
			}
			
			let instance = this.axios.create({
				baseURL: this.$parent.$parent.AJAX_URL
			});
		
			instance.defaults.headers.common['Authorization'] = this.$parent.$parent.user.access_token

		    this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/cart', {
		    	id_book: +this.$route.params.id,
				count: +this.count
		    }, config)  
		    .then(function (response) {
		    	if (!response.data.success) {
		    		console.log(response.data.message)
		    	} else {
		    		self.success = response.data.message
		    	}
		    	
			})
			.catch(function (error) {
			    console.log(error);
			});
  		},
		changeCount(operation) {
			this.count += operation
			if (this.count <= 1) {
				this.count = 1;
			}
  		},
  }
}
</script>


<style>

</style>