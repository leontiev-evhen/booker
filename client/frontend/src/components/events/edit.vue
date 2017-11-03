<template>
  	<div class="edit">
	  	<h3 class="my-4"><i class="fa fa-list-alt" aria-hidden="true"></i> Edit event</h3>
		<form @submit.prevent="edit">

			
			<div class="form-group">
				<label for="text">Description:</label>
				<textarea class="form-control" rows="5" id="comment"></textarea>
			</div>
			<button type="submit" class="btn btn-primary" @click="close">Update</button>
			<button type="submit" class="btn btn-danger">Delete</button>
		</form>
	</div>
</template>


<script>

export default {
  	name: 'edit',
 	 data() {
		return {
      		user: '',
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
		close: function () {
			window.close();
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
		
			this.axios.put(this.$parent.$parent.AJAX_URL + '/booker/client/api/users/' + this.$route.params.id, {
					name: (this.name) ? this.name : this.user.name,
					email: (this.email) ? this.email : this.user.email,
					discaunt: (this.discaunt) ? +this.discaunt : +this.user.discaunt,
					status: (this.status) ? +this.status : +this.user.status,
				}, config)  
				.then((response) => {

					if (response.status == 200) {
						if (!response.data.success) {
							console.log(response.data.message)
						} else {
							location.href = '/admin/users'
						}
					} else {
						console.log(response.data.message)
					}
				})
 		}
  	},
    created() {

    this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/users/' + this.$route.params.id).then((response) => {

        if (response.status == 200) {
            if (response.data.status) {
              	this.user = response.data.data
              	let self = this
              	
              	let instance = this.axios.create({
  					baseURL: this.$parent.$parent.AJAX_URL
				});

  				instance.defaults.headers.common['Authorization'] = this.user.token
    			
    			this.axios.get(this.$parent.$parent.AJAX_URL + '/book/client/api/orders/all/user')  
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
