<template>
	<div>
	<div class="user_info col-md-6">
		<h3 class="my-4">Hello {{this.$parent.$parent.user.name}}</h3>
		<ul class="description">
			<li><span class="glyphicon glyphicon-envelope"></span>{{this.$parent.$parent.user.email}}</li>
		</ul>
	</div>
	<div class="col-md-6">
		<h2>Pre-Order</h2>
		<ol class="list_status">
			<li v-for="(value, key) in orders">
				<span>{{value.name}}</span>
				<span v-if="value.status == 1" class="glyphicon glyphicon-star" :id="value.ID" :status="0" @click="changeStatus"></span>
				<span v-else  @click="changeStatus" class="glyphicon glyphicon-star-empty" :id="value.ID" :status="1"></span>
			</li>
		</ol>
		<p class="success_m">{{success}}</p>
	</div>
	</div>
</template>

<script>
	export default {
  	name: 'User',
  	data() {
  		return {
			orders: '',
			status: '',
			options: {1: 'Active', 0: 'Inactive'},
			success: ''
  		}
  	},
  	methods: {
  	
  	},
  	created() {
  	
  		if (!this.$parent.$parent.user) {
  			location.href = '/';
  		}

  		let instance = this.axios.create({
  			baseURL: this.$parent.$parent.AJAX_URL
		});

		
  		let self = this
  	

  		instance.defaults.headers.common['Authorization'] = this.$parent.$parent.user.access_token

	    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/customers/' + this.$parent.$parent.user.id + '/orders')  
    	.then(function (response) {
		  	if (response.status == 200) {
	            if (response.data.success) {
	            	self.orders = response.data.data
	            } else {
	            	//self.$parent.user = null
	              	self.$parent.logout()
	            }
	      	} else {
	        	console.log(response.data.message)
	      	}
		}) 

	
  	},
  	methods: {
  		changeStatus: function(e) {
  		
            	let config = {
				  	headers: {
				    	'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
				  	}
				}

				let id = e.target.attributes.id.nodeValue
				let status = e.target.attributes.status.nodeValue
				let gclass = e.target.attributes.class.nodeValue
	    
			    this.axios.put(this.$parent.AJAX_URL + '/rest/client/api/orders', {
			    	status: +status,
			    	id: +id
			    }, config)  
			    .then((response) => {

			        if (response.status == 200) {
			            if (!response.data.success) {
			              	this.$parent.logout()
			            } else {
			              	this.success = response.data.message

			              	if (gclass == 'glyphicon glyphicon-star-empty') {

			              		e.target.attributes.class.nodeValue = 'glyphicon glyphicon-star'

			              		e.target.attributes.status.nodeValue = 0
			              	} else {
			              		e.target.attributes.class.nodeValue = 'glyphicon glyphicon-star-empty'
			              		e.target.attributes.status.nodeValue = 1
			              	}
			              	
			            }
			        } else {
			            console.log(response.data.message)
			        }
		    	})
        	
  		}
  	}
}
</script>
<style>

</style>

