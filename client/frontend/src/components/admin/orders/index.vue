<template>
  	<div class="order">
  		
	  	<h3 class="my-4">Orders</h3>

  		<div class="list-group">
			 <div class="table">
	            <div class="row">
	                <div class="cell th">№</div>
	                <div class="cell th">Name</div>
	                <div class="cell th">
	                	<a href="#" @click="sort">Create</a>
	                	<i class="fa fa-long-arrow-down" aria-hidden="true"></i>
	                	<i class="fa fa-long-arrow-up" aria-hidden="true"></i>
	                </div>
	                <div class="cell th">Status</div>
	                <div class="cell th">Show info</div>
	            </div>
	            <div class="row" v-for="(order, key) in orders">
	                <div class="cell">{{key+1}}</div>
	                <div class="cell">
	                	<a :href="'/admin/customer/edit/' + order.id_customer">{{order.CUSTOMER_NAME}}</a>
	                </div>
	                <div class="cell">
	                	{{order.create_at}}
	                </div>
	                <div class="cell">
	                	<div v-if="showStatus.id == order.id">
	                		<select v-model="status" class="form-control" @change="changeStatus(order)">
	                			<option v-for="status in orderInfo[order.id].status_orders" :value="status.id">{{status.name}}</option>
	                		</select>
	                	</div>
	                	<div v-else>{{order.STATUS_NAME}}</div>
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
						
							<button @click="hideInfo(order)" class="btn btn-warning btn-hide"><i  aria-hidden="true" class="fa fa-arrow-up"></i></button>
					
						</div>
					</transition>
	            </div>
	        </div>
		</div>

	</div>
</template>


<script>


export default {
  	name: 'index',
 	 data() {
		return {
      		orders: '',
      		status: '',
      		orderInfo: {},
      		showStatus: {id: 0},
      		showInfo: {id: 0},
      		sortOrder: true
		}
  	},
 	methods: {
 		sort: function() {
 			this.sortOrder = !this.sortOrder
 			if (this.sortOrder) {
				this.orders.sort((a, b) => {
					return b.create_at.toString().localeCompare(a.create_at);
				});
			} else {
				this.orders.sort((a, b) => {
					return a.create_at.toString().localeCompare(b.create_at);
				});
			}
 		},
 		moreInfo: function(id) {
			this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/orders/' + id).then((response) => {

		        if (response.status == 200) {
		            if (response.data.status) {
						this.orderInfo[id] = response.data.data
		         		this.showStatus.id = id
		         		this.showInfo.id = id
		            } else {
		              	console.log(response.data.message)
		            }
		        } else {
		            console.log(response.data.message)
		        }
			})
 		},
 		hideInfo: function(order) {
 			this.showInfo.id = 0
 			this.showStatus.id = 0
 		},
 		changeStatus: function(order) {
 			let self = this
 			let config = {
				headers: {
					'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
				}
			}
		
			this.axios.put(this.$parent.$parent.AJAX_URL + '/book/client/api/orders/' + order.id, {
					id_status: +this.status,
				}, config)  
				.then((response) => {

					if (response.status == 200) {
						if (!response.data.success) {
							console.log(response.data.message)
						} else {

							this.orderInfo[order.id].status_orders.forEach (function(item, i, arr){
								if (item.id == self.status) {
							  		order.STATUS_NAME = item.name
								}
							})
							
						}
					} else {
						console.log(response.data.message)
					}
				})
 		}
 	
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/orders').then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.orders = response.data.data

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
