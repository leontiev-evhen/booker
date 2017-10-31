<template>
  	<div class="edit">
	  	<h3 class="my-4">Edit customer</h3>
		<form @submit.prevent="edit">
			<div class="form-group">
			    <label for="text">Name:</label>
				<p :class="{ 'control': true }"><input v-model="name" v-validate="'required'" :class="{'input form-control': true, 'is-danger': errors.has('name') }" type="text" name="name" v-default-value="customer.name"></p>
				<span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Email:</label>
				<p :class="{ 'control': true }"><input v-model="email" v-validate="'required|alpha'" :class="{'input form-control': true, 'is-danger': errors.has('email') }" type="text" name="email" v-default-value="customer.email"></p>
				<span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
			</div>
			<div class="form-group">
			    <label for="text">Discaunt:</label>
			    <input type="text" class="form-control" v-default-value="customer.discaunt" v-model="discaunt">
			</div>
			<div class="form-group">
				<label for="text">Status:</label><br>
				<label for="text">Active:</label>
				<input name="status" type="radio" :value="1" @click="isStatus($event)" :checked="customer.status == 1">
				<label for="text">Inactive:</label>
				<input name="status" type="radio" :value="0" @click="isStatus($event)" :checked="customer.status == 0">
			</div>
			
			<button type="submit" class="btn btn-primary">Edit</button>
		</form>
		<div v-if="orders">
			<h4 class="my-4">Orders</h4>
			<div class="table">
	            <div class="row">
	                <div class="cell th">№</div>
	                <div class="cell th">Create</div>
	                <div class="cell th">Status</div>
	                <div class="cell th">Show info</div>
	            </div>
	            <div class="row" v-for="(order, key) in orders">
	                <div class="cell">{{key+1}}</div>
	                <div class="cell">
	                	{{order.create_at}}
	                </div>
	                <div class="cell">
	                	{{order.STATUS_NAME}}
	                </div>
	                <div class="cell">
						<button @click="moreInfo(order.id)" class="btn btn-warning"><i  aria-hidden="true" class="fa fa-info-circle"></i></button>
					</div>
					<transition name="fade">
						<div v-if="showInfo.id == order.id" class="col-md-12 more">
							<div class="row">
								<div class="col-lg-6">
									<h4>Order info</h4>
									<span>Sum: <i class="fa fa-eur" aria-hidden="true"></i>{{orderInfo[order.id].sum}}</span><br>
									<span>Payment system: <b>{{orderInfo[order.id].NAME_PAYMENT}}</b></span>
								</div>
							<div class="col-lg-6">
								<h4>Books</h4>
								<ol>
									<li v-for="book in orderInfo[order.id].books">
										<a :href="'/admin/book/edit/' + book.id">{{book.name}}</a>
										<i class="fa fa-eur" aria-hidden="true"></i>{{book.price}} 
									</li>
								</ol>
							</div>
							</div>
						
							<button @click="hideInfo()" class="btn btn-warning btn-hide"><i  aria-hidden="true" class="fa fa-arrow-up"></i></button>
					
						</div>
					</transition>
	            </div>
	    	</div>
    	</div>
	</div>
</template>


<script>

export default {
  	name: 'edit',
 	 data() {
		return {
      		customer: '',
      		name: '',
			email: '',
			discaunt: '',
			status: '',
			orders: '',
			showInfo: {id: 0},
			orderInfo: {},
		}
  	},
 	methods: {
 		moreInfo: function(id) {
			this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/orders/' + id).then((response) => {

		        if (response.status == 200) {
		            if (response.data.status) {
						this.orderInfo[id] = response.data.data
		         		this.showInfo.id = id
		            } else {
		              	console.log(response.data.message)
		            }
		        } else {
		            console.log(response.data.message)
		        }
			})
 		},

 		hideInfo: function() {
 			this.showInfo.id = 0
 		},

 		isStatus: function(event) {
 			this.status = event.target.value
 		},
 		edit: function() {
	
			let config = {
				headers: {
					'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
				}
			}
		
			this.axios.put(this.$parent.$parent.AJAX_URL + '/book/client/api/customers/' + this.$route.params.id, {
					name: (this.name) ? this.name : this.customer.name,
					email: (this.email) ? this.email : this.customer.email,
					discaunt: (this.discaunt) ? +this.discaunt : +this.customer.discaunt,
					status: (this.status) ? +this.status : +this.customer.status,
				}, config)  
				.then((response) => {

					if (response.status == 200) {
						if (!response.data.success) {
							console.log(response.data.message)
						} else {
							location.href = '/admin/customers'
						}
					} else {
						console.log(response.data.message)
					}
				})
 		}
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/customers/' + this.$route.params.id).then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.customer = response.data.data
              	let self = this
              	
              	let instance = this.axios.create({
  					baseURL: this.$parent.$parent.AJAX_URL
				});

  				instance.defaults.headers.common['Authorization'] = this.customer.token
    			
    			this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/orders/all/customer')  
		    	.then(function (response) {
				  	if (response.status == 200) {
			            if (response.data.success) {
			            	self.orders = response.data.data
			            } else {
			            	console.log(response.data.message)
			            }
			      	} else {
			        	console.log(response.data.message)
			      	}
				})

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
