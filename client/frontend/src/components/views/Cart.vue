<template>
	<div class="cart" v-if="books.length > 0">
		<h3 class="my-4">Cart</h3>
		<div class="row">
			<table class="table table-hover">
				<thead>
				<tr>
				 	<th>№</th>
				  	<th>Name</th>
				  	<th>Count</th>
				  	<th>Price <i class="fa fa-eur" aria-hidden="true"></i></th>
				  	<th>Discount <i class="fa fa-eur" aria-hidden="true"></i></th>
				  	<th>Sum</th>
				</tr>
				</thead>
				<tbody>
					<tr v-for="(book, key) in books">
					  	<td>{{key+1}}</td>
					  	<td><a :href="'/book/' + book.id">{{book.name}}</a></td>
					  	<td>
							<button @click="changeCount(key, 0)" class="btn btn-default btn-cart mt-20">-</button>
							<span class="count">{{book.count}}</span>
							<button @click="changeCount(key, 1)" class="btn btn-default btn-cart mt-20">+</button>
					  	</td>
					  	<td>{{book.price}}</td>
					  	<td> <span v-if="book.discaunt > 0"> - {{book.discaunt}}</span>
					  	<div class="link_remove">
					  		<a href="#" @click="remove(book.id)"><i class="fa fa-times" aria-hidden="true"></i></a>
					  	</div>
					  	</td>
				
					  	<td>{{(book.price - book.discaunt) * book.count}}</td>
					</tr>
					<tr>
						<td colspan="4"></td>
						<td><b>Sum</b> <i class="fa fa-eur" aria-hidden="true"></i></td>
						<td>{{Total}}</td>
					</tr>
					<tr>
						<td colspan="4"></td>
						<td><b>Customer discount </b> <i class="fa fa-eur" aria-hidden="true"></i></td>
						<td>{{discaunt}}</td>
					</tr>
					<tr>
						<td colspan="4"></td>
						<td><b>Total</b> <i class="fa fa-eur" aria-hidden="true"></i></td>
						<td>{{Total - discaunt}}</td>
					</tr>
				</tbody>
				</table>
				
				<h5 class="col-md-12">Choose payment system</h5>
				<ol>
					<li v-for="item in payment_systems">
						<input type="radio" name="payment" :value="item.id" @click="isPayment($event)" v-validate="'required'">
						<label>{{item.name}}</label>
					</li>
				</ol>
				<span class="help is-danger" v-show="errors.has('payment')">{{ errors.first('payment') }}</span>
				<div class="col-md-12">
					<button type="button" class="btn btn-primary" @click="confirm">Confirm</button>
				</div>
		</div>
	</div>
	<div v-else>
		<h3 class="my-4">Cart is empty</h3>
	</div>
</template>
<script>

export default {
name: 'cart',
data () {
    return {
    	books: '',
    	payment_systems: '',
    	payment: '',
    	discaunt: 0,
    	total: ''
    }
},
created() {
  	if (this.$parent.$parent.user) {
		let instance = this.axios.create({
			baseURL: this.$parent.$parent.AJAX_URL
		});
		
		instance.defaults.headers.common['Authorization'] = this.$parent.$parent.user.access_token
			
		this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/cart').then((response) => {
			
          if (response.status == 200) {
            if (response.data.status) {
              this.books = response.data.data.books
              this.payment_systems = response.data.data.payment_systems
              this.discaunt = response.data.data.customer.discaunt
            } else {
              console.log(response.data.message)
            }
          } else {
            console.log(response.data.message)
          }
    	})
  	} 
},
methods: {
	isPayment: function(event) {
		this.payment = event.target.value
	},
	changeCount(key, operation) {

		if (operation > 0) {
			this.books[key].count++
		} else {
			this.books[key].count--
			if (this.books[key].count <= 1) {
				this.books[key].count = 1
			}
		}
  	},
  	remove: function(id, key) {
  		let answer = confirm('Are you sure?')
 			if (answer) {
		  		this.axios.delete(this.$parent.$parent.AJAX_URL + '/book/client/api/cart/' + id).then((response) => {
					
		          if (response.status == 200) {
		            if (response.data.status) {
		            	location.reload()
		            } else {
		              console.log(response.data.message)
		            }
		          } else {
		            console.log(response.data.message)
		          }
		    	})
  		}
  	},
  	confirm: function () {
  		this.$validator.validateAll().then((result) => {
		    if (result) {
		    	let self = this
  				let config = {
				  headers: {
				    'Content-Type' : 'application/x-www-form-urlencoded'
				  }
				}
    
			    this.axios.post(this.$parent.$parent.AJAX_URL + '/book/client/api/orders', {
			    	books: this.books,
			    	sum: +this.total,
			    	payment_id: +this.payment
			    }, config)  
			    .then(function (response) {
			    	if (response.data.success) {

			    		self.axios.delete(self.$parent.$parent.AJAX_URL + '/book/client/api/cart').then((response) => {
					
				          if (response.status == 200) {
				            if (response.data.status) {
				            	location.href = '/success'
				            } else {
				              console.log(response.data.message)
				            }
				          } else {
				            console.log(response.data.message)
				          }
				    	})
			    	} 
			    	
				})
				.catch(function (error) {
				    console.log(error);
				});
  			}
     	});
  	}
},
computed: {
    Total: function () {
      	let total = 0
		let sum = 0

		for (var key in this.books) {
			if (this.books[key].discaunt) {
				sum = this.books[key].price - this.books[key].discaunt
			} else {
				sum = this.books[key].price
			}
			sum *= this.books[key].count
			total += sum
		}
		this.total = total - this.discaunt
		return total
    }
  },
}
</script>


<style>

</style>