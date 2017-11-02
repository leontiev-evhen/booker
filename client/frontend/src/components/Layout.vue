<template>
  	<div class="layout">
  		<div class="row auth_block">
			<div class="col-md-1 link-home">
				<a href="/#/"><i class="fa fa-home" aria-hidden="true"></i></a>
			</div>
			<div class="col-md-offset-8 col-md-3">
				<div>
					<i class="fa fa-user" aria-hidden="true"></i> Hello {{name}} 
					<a href="#" @click="logout">
						logout <i class="fa fa-sign-out" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		</div>
		<router-view @login="login" @logout="logout"></router-view>
	</div>
</template>


<script>
export default {
	name: 'layout',
	data() {
		return {
			name: this.$parent.user ? this.$parent.user.name : ''
		}
	},
  	created() {

		if (this.isJson(localStorage.getItem('profile')) && localStorage.getItem('profile')) {
			let self = this;
			let user = JSON.parse(localStorage.getItem('profile'));

			if (user.access_token) {
				let instance = this.axios.create({
					baseURL: this.$parent.AJAX_URL
				});
				instance.defaults.headers.common['Authorization'] = user.access_token

				this.axios.get(this.$parent.AJAX_URL + '/booker/client/api/auth')  
				.then(function (response) {
				if (response.status == 200) {
					if (!response.data.success) {
						self.$parent.logout();
					}
				} else {
					console.log(response.data.message);
				}
				})
			} else {
				this.$parent.logout();
			}
		} else {
			this.$parent.logout();
		}
		
  	},
  	methods: {
  		isJson: function (str) {
		   try {
		        JSON.parse(str);
		   } 	catch (e) {
		      	return false;
		   }
		    	return true;
		},
		login: function() {
	      this.$emit('login')
	   },
	   logout: function() {
	        this.$emit('logout')
	   }
  	}
}
</script>


<style>
</style>
