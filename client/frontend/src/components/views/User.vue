<template>
	<div>
	<div class="user_info col-md-6">
		<h3 class="my-4">Hello {{this.$parent.$parent.user.name}}</h3>
		
		<p>
			<i class="fa fa-envelope" aria-hidden="true"></i> 
			<a :href="'mailto:'+ this.$parent.$parent.user.email">{{this.$parent.$parent.user.email}}</a>
		</p>

	</div>
	<div class="col-md-12">
		<h4>Orders</h4>
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
									<a :href="'/book/' + book.id">{{book.name}}</a>
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
  	name: 'User',
  	data() {
  		return {
			orders: '',
			showInfo: {id: 0},
			orderInfo: {},
  		}
  	},
  	methods: {
  	
  	},
  	created() {
  	
  		if (!this.$parent.$parent.user) {
  			location.href = '/';
  		}
  		let self = this
	            	
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
 		}
  	}
}
</script>
<style>

</style>

